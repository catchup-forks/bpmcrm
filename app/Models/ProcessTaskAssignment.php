<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Represents a business process task assignment definition.
 *
 * @property string $id
 * @property string process_task_id
 * @property string assignment_id
 * @property string assignment_type
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
final class ProcessTaskAssignment extends Model
{    

    protected $fillable = [
        'process_id',
        'process_task_id',
        'assignment_id',
        'assignment_type'
    ];

    /**
     * Validation rules
     *
     * @return array{process_task_id: string, assignment_id: string, assignment_type: string}
     */
    public static function rules(): array
    {
        return [
            'process_task_id' => 'required|exists:processes,id',
            'assignment_id' => 'required',
            'assignment_type' => 'required|in:USER,GROUP',
        ];
    }

}
