<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Models\Process;
use App\Traits\SerializeToIso8601;

/**
 * Represents a business process category definition.
 *
 * @property string $id
 * @property string $name
 * @property string $status
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 *  * @OA\Schema(
 *   schema="ProcessCategoryEditable",
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="status", type="string", enum={"ACTIVE", "INACTIVE"}),
 * ),
 * @OA\Schema(
 *   schema="ProcessCategory",
 *   allOf={@OA\Schema(ref="#/components/schemas/ProcessCategoryEditable")},
 *   @OA\Property(property="id", type="string", format="id"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
final class ProcessCategory extends Model
{
    use SerializeToIso8601;

    protected $fillable = [
        'name',
        'status'
    ];

    /**
     * @return array{name: mixed[]|string, status: string}
     */
    public static function rules($existing=null): array
    {
        $rules = [
            'name' => 'required|string|max:100|unique:process_categories,name',
            'status' => 'required|string|in:ACTIVE,INACTIVE'
        ];
        if ($existing) {
            $rules['name'] = [
                'required','max:100','string',
                Rule::unique('process_categories')->ignore($existing->id, 'id')
            ];
        }

        return $rules;
    }

    /**
     * Get processes
     *
     * @return HasMany
     */
    public function processes()
    {
        return $this->hasMany(Process::class);
    }
}
