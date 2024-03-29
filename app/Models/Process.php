<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Exception\TaskDoesNotHaveUsersException;
use App\Nayra\Contracts\Bpmn\ActivityInterface;
use App\Nayra\Contracts\Bpmn\ScriptTaskInterface;
use App\Nayra\Contracts\Bpmn\ServiceTaskInterface;
use App\Nayra\Contracts\Storage\BpmnDocumentInterface;
use App\Nayra\Storage\BpmnDocument;
use App\Traits\SerializeToIso8601;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Represents a business process definition.
 *
 * @property string $id
 * @property string $process_category_id
 * @property string $summary_screen_id
 * @property string $user_id
 * @property string $bpmn
 * @property string $description
 * @property string $name
 * @property string $status
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 * @OA\Schema(
 *   schema="ProcessEditable",
 *   @OA\Property(property="process_category_id", type="string", format="id"),
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="description", type="string"),
 *   @OA\Property(property="status", type="string", enum={"ACTIVE", "INACTIVE"}),
 * ),
 * @OA\Schema(
 *   schema="Process",
 *   allOf={@OA\Schema(ref="#/components/schemas/ProcessEditable")},
 *   @OA\Property(property="user_id", type="string", format="id"),
 *   @OA\Property(property="id", type="string", format="id"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
final class Process extends Model implements HasMedia
{
    use HasMediaTrait;
    use SerializeToIso8601;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * BPMN data will be hidden. It will be able by its getter.
     *
     * @var array
     */
    protected $hidden = [
        'bpmn'
    ];

    /**
     * Parsed process BPMN definitions.
     *
     * @var \app\Nayra\Contracts\Storage\BpmnDocumentInterface
     */
    private $bpmnDefinitions;

    /**
     * Category of the process.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ProcessCategory::class, 'process_category_id');
    }

    public function summaryScreen()
    {
       return $this->belongsTo(Screen::class, 'summary_screen_id');
    }

    /**
     * Validation rules.
     *
     * @param null $existing
     *
     * @return array{name: string, description: string, status: string, process_category_id: string, bpmn: string}
     */
    public static function rules($existing = null): array
    {
        $rules = [
            'name' => 'required|unique:processes,name',
            'description' => 'required',
            'status' => 'in:ACTIVE,INACTIVE',
            'process_category_id' => 'nullable|exists:process_categories,id',
            'bpmn' => 'nullable',
        ];

        if ($existing) {
            // ignore the unique rule for this id
            $rules['name'] = [
                'required',
                'string',
                Rule::unique('processes')->ignore($existing->id, 'id')
            ];
        }

        return $rules;
    }

    /**
     * Get the creator/author of this process.
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the process definitions from BPMN field.
     *
     * @return ProcessMaker\Nayra\Contracts\Storage\BpmnDocumentInterface
     */
    public function getDefinitions()
    {
        if (empty($this->bpmnDefinitions)) {
            $this->bpmnDefinitions = app(BpmnDocumentInterface::class, ['process' => $this]);
            if ($this->bpmn) {
                $this->bpmnDefinitions->loadXML($this->bpmn);
                //Load the collaborations if exists
                $collaborations = $this->bpmnDefinitions->getElementsByTagNameNS(BpmnDocument::BPMN_MODEL, 'collaboration');
                foreach($collaborations as $collaboration) {
                    $collaboration->getBpmnElementInstance();
                }
            }
        }
        return $this->bpmnDefinitions;
    }

    /**
     * Get the path of the process templates.
     *
     * @return string
     */
    public static function getProcessTemplatesPath()
    {
        return Storage::disk('process_templates')->path('');
    }

    /**
     * Get a process template by name.
     *
     * @param string $name
     *
     * @return string
     */
    public static function getProcessTemplate($name)
    {
        return Storage::disk('process_templates')->get($name);
    }

    /**
     * Category of the process.
     *
     * @return BelongsTo
     */
    public function requests()
    {
        return $this->hasMany(ProcessRequest::class);
    }

    /**
     * Category of the process.
     *
     * @return BelongsTo
     */
    public function collaborations()
    {
        return $this->hasMany(ProcessCollaboration::class);
    }

    /**
     * Get the user to whom to assign a task.
     *
     * @param ActivityInterface $activity
     * @param TokenInterface $token
     *
     * @return User
     */
    public function getNextUser(ActivityInterface $activity, ProcessRequestToken $token)
    {
        $default = $activity instanceof ScriptTaskInterface
            || $activity instanceof ServiceTaskInterface ? 'script' : 'requestor';
        $assignmentType = $activity->getProperty('assignment', $default);
        $user = match ($assignmentType) {
            'cyclical' => $this->getNextUserCyclicalAssignment($activity->getId()),
            'requestor' => $token->getInstance()->user_id,
            'manual', 'self_service' => null,
            default => null,
        };
        return $user ? User::where('id', $user)->first() : null;
    }

    /**
     * Get the next user in a cyclical assignment.
     *
     * @param string $processTaskUuid
     *
     * @return binary
     * @throws TaskDoesNotHaveUsersException
     */
    private function getNextUserCyclicalAssignment($processTaskUuid)
    {
        $last = ProcessRequestToken::where('process_id', $this->id)
            ->where('element_id', $processTaskUuid)
            ->orderBy('created_at', 'desc')
            ->first();
        $users = $this->getAssignableUsers($processTaskUuid);
        if (empty($users)) {
            throw new TaskDoesNotHaveUsersException($processTaskUuid);
        }
        sort($users);
        if ($last) {
            foreach ($users as $user) {
                if ($user > $last->user_id) {
                    return $user;
                }
            }
        }
        return $users[0];
    }

    /**
     * Get an array of all assignable users to a task.
     *
     * @param string $processTaskUuid
     */
    public function getAssignableUsers($processTaskUuid): array
    {
        $assignments = ProcessTaskAssignment::select(['assignment_id', 'assignment_type'])
                ->where('process_id', $this->id)
                ->where('process_task_id', $processTaskUuid)
                ->get();
        $users = [];
        foreach ($assignments as $assignment) {
            if ($assignment->assignment_type === 'user') {
                $users[$assignment->assignment_id] = $assignment->assignment_id;
            } else {
                $this->getConsolidatedUsers($assignment->assignment_id, $users);
            }
        }
        return array_values($users);
    }

    /**
     * Get a consolidated list of users within groups.
     *
     * @param binary $group_id
     *
     * @return array
     */
    private function getConsolidatedUsers($group_id, array &$users)
    {
        $groupMembers = GroupMember::where('group_id', $group_id)->get();
        foreach ($groupMembers as $groupMember) {
            if ($groupMember->member_type === 'user') {
                $users[$groupMember->member_id] = $groupMember->member_id;
            } else {
                $this->getConsolidatedUsers($groupMember->member_id, $users);
            }
        }
        return $users;
    }

    /**
     * Get a list of the process start events.
     *
     * @return mixed[]
     */
    public function getStartEvents(): array
    {
        $definitions = $this->getDefinitions();
        $response = [];
        $startEvents = $definitions->getElementsByTagNameNS(BpmnDocument::BPMN_MODEL, 'startEvent');
        foreach ($startEvents as $startEvent) {
            $response[] = $startEvent->getBpmnElementInstance()->getProperties();
        }
        return $response;
    }

    /**
     * Process events relationship.
     *
     * @return \app\Models\ProcessEvents
     */
    public function events(): ProcessEvents
    {
        $query = $this->newQuery();
        $query->where('id', $this->id);
        return new ProcessEvents($query, $this);
    }
}
