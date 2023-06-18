<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * ProcessVersion is used to store the historical version of a process.
 *
 * @property string id
 * @property string bpmn
 * @property string name
 * @property string process_category_id
 * @property string process_id
 * @property string status
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
final class ProcessVersion extends Model
{
    /**
     * Attributes that are not mass assignable.
     *
     * @var array $fillable
     */
    protected $guarded = [
        'id',
        'bpmn',
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
     * Validation rules.
     *
     * @return array{name: string, status: string, process_category_id: string, process_id: string}
     */
    public static function rules(): array
    {
        return [
            'name' => 'required',
            'status' => 'in:ACTIVE,INACTIVE',
            'process_category_id' => 'exists:process_categories,id',
            'process_id' => 'exists:processes,id',
        ];
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
     * Get the process to which this version points to.
     *
     */
    public function processCategory()
    {
        return $this->belongsTo(ProcessCategory::class, 'process_category_id');
    }

}
