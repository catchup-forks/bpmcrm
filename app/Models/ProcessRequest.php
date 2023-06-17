<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use App\Nayra\Engine\ExecutionInstanceTrait;
use App\Traits\SerializeToIso8601;

/**
 * Represents an Eloquent model of a Request which is an instance of a Process.
 *
 * @property string $id
 * @property string $process_id
 * @property string $user_id
 * @property string $process_collaboration_id
 * @property string $participant_id
 * @property string $name
 * @property string $status
 * @property string $data
 * @property \Carbon\Carbon $initiated_at
 * @property \Carbon\Carbon $completed_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property Process $process
 *
 * @OA\Schema(
 *   schema="requestsEditable",
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="process_id", type="string", format="id"),
 *   @OA\Property(property="callable_id", type="string", format="id"),
 *   @OA\Property(property="data", type="string", format="json"),
 *   @OA\Property(property="status", type="string", enum={"DRAFT", "ACTIVE", "COMPLETED"}),
 * ),
 * @OA\Schema(
 *   schema="requests",
 *   allOf={@OA\Schema(ref="#/components/schemas/requestsEditable")},
 *   @OA\Property(property="id", type="string", format="id"),
 *
 *   @OA\Property(property="process_collaboration_id", type="string", format="id"),
 *   @OA\Property(property="user_id", type="string", format="id"),
 *   @OA\Property(property="participant_id", type="string", format="id"),
 *
 *   @OA\Property(property="process_category_id", type="string", format="id"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class ProcessRequest extends Model implements ExecutionInstanceInterface
{

    use ExecutionInstanceTrait;
    use SerializeToIso8601;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
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
        'data'
    ];

    /**
     * The binary UUID attributes that should be converted to text.
     *
     * @var array
     */
    protected $ids = [
        'process_id',
        'process_collaboration_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime:c',
        'initiated_at' => 'datetime:c',
        'data' => 'array'
    ];

    /**
     * Associated records that can be included with this model
     *
     * @var array
     */
    public static $allowedIncludes = [
        'assigned',
        'process',
        'participants',
    ];

    /**
     * Boot the model as a process instance.
     *
     * @param array $argument
     */
    public function __construct(array $argument=[])
    {
        parent::__construct($argument);
        $this->bootElement([]);
    }

    /**
     * Validation rules.
     *
     * @param null $existing
     *
     * @return array
     */
    public static function rules($existing = null)
    {
        $rules = [
            'name' => 'required|unique:process_requests,name',
            'data' => 'required',
            'status' => 'in:ACTIVE,COMPLETED,ERROR',
            'process_id' => 'required|exists:processes,id',
            'process_collaboration_id' => 'nullable|exists:process_collaborations,id',
            'user_id' => 'exists:users,id',
        ];

        if ($existing) {
            // ignore the unique rule for this id
            $rules['name'] = [
                'required',
                'string',
                'max:100',
                Rule::unique('process_requests')->ignore($existing->id, 'id')
            ];
        }

        return $rules;
    }

    /**
     * Determines if a user has participated in this application.  This is done by checking if any delegations
     * match this application and passed in user.
     *
     * @param User $user User to check
     *
     * @return boolean True if the user participated in this Case in some way
     */
    public function hasUserParticipated(User $user)
    {
        return $this->tokens()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Get tokens of the request.
     *
     */
    public function tokens()
    {
        return $this->hasMany(ProcessRequestToken::class);
    }

    /**
     * Get the creator/author of this request.
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get collaboration of this request.
     *
     */
    public function collaboration()
    {
        return $this->belongsTo(ProcessCollaboration::class, 'process_collaboration_id');
    }

    /**
     * Get the creator/author of this request.
     *
     */
    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    /**
     * Get users of the request.
     *
     */
    public function assigned()
    {
        return $this->hasMany(ProcessRequestToken::class)
            ->with('user')
            ->whereNotIn('element_type' , ['scriptTask']);
    }

    /**
     * Filter process started with user
     *
     * @param $query
     *
     * @param $id User id
     */
    public function scopeStartedMe($query, $id)
    {
        $query->where('user_id', '=', $id);
    }

    /**
     * Filter process not completed
     *
     * @param $query
     */
    public function scopeInProgress($query)
    {
        $query->where('status' , '=', 'ACTIVE');
    }

    /**
     * Filter process completed
     *
     * @param $query
     */
    public function scopeCompleted($query)
    {
        $query->where('status' , '=', 'COMPLETED');
    }

    /**
     * Returns the list of users that have participated in the request
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function participants()
    {
        return $this->hasManyThrough(User::class, ProcessRequestToken::class, 'process_request_id', 'id', $this->getKeyName(), 'user_id')
            ->distinct();
    }

    /**
     * Returns the summary data in an array key/value
     */
    public function summary()
    {
        $result = [];
        if (is_array($this->data)) {
            foreach($this->data as $key => $value) {
                $result[] = [
                    'key' => $key,
                    'value' => $value
                ];
            }
        }

        return $result;
    }
}
