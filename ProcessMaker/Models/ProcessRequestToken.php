<?php

namespace ProcessMaker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use ProcessMaker\Nayra\Bpmn\TokenTrait;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;

/**
 * ProcessRequestToken is used to store the state of a token of the
 * Nayra engine
 *
 * @property string $id
 * @property string $process_request_id
 * @property string $user_id
 * @property string $element_id
 * @property string $element_type
 * @property string $status
 * @property \Carbon\Carbon $completed_at
 * @property \Carbon\Carbon $due_at
 * @property \Carbon\Carbon $initiated_at
 * @property \Carbon\Carbon $riskchanges_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property ProcessRequest $request
 *
 */
class ProcessRequestToken extends Model implements TokenInterface
{
    use TokenTrait;

    /**
     * Attributes that are not mass assignable.
     *
     * @var array $guarded
     */
    protected $guarded = [
        'id',
        'updated_at',
        'created_at',
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
     * The binary UUID attributes that should be converted to text.
     *
     * @var array
     */
    protected $ids = [
        'process_id',
        'process_request_id',
        'user_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'previousUser'
    ];

    /**
     * Boot application as a process instance.
     *
     * @param array $argument
     */
    public function __construct(array $argument=[])
    {
        parent::__construct($argument);
        $this->bootElement([]);
    }

    /**
     * Get the process to which this version points to.
     *
     */
    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * Get the request of the token.
     *
     */
    public function processRequest()
    {
        return $this->belongsTo(ProcessRequest::class, 'process_request_id');
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
     * Returns the user that sent the task or
     */
    public function getPreviousUserAttribute()
    {
        $query = ProcessRequestToken::query();
        $query->where('process_request_id', $this->process_request_id)
                ->where('id', '!=', $this->id)
                ->where('status', 'ACTIVE')
                ->orderByDesc('completed_at');
        $last = $query->get()->last();
        if (empty($last)) {
            return ProcessRequest::find($this->process_request_id)
                    ->user;
        }
        return $last->user;
    }

    /**
     * Get the BPMN definition of the element where the token is.
     *
     * @return array
     */
    public function getDefinition()
    {
        $definitions = $this->processRequest->process->getDefinitions();
        $element = $definitions->findElementById($this->element_id);
        if (!$element) {
            return [];
        }
        return $element->getBpmnElementInstance()->getProperties();
    }

    /**
     * Get the form assigned to the task.
     *
     * @return Form
     */
    public function getForm()
    {
        $definition = $this->getDefinition();
        return Form::find($definition['formRef']);
    }
}