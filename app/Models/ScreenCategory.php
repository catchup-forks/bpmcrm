<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Screen;
use App\Traits\SerializeToIso8601;

/**
 * Represents a business screen category definition.
 *
 * @property string $id
 * @property string $name
 * @property string $status
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 *  * @OA\Schema(
 *   schema="ScreenCategoryEditable",
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="status", type="string", enum={"ACTIVE", "INACTIVE"}),
 * ),
 * @OA\Schema(
 *   schema="ScreenCategory",
 *   allOf={@OA\Schema(ref="#/components/schemas/ScreenCategoryEditable")},
 *   @OA\Property(property="id", type="string", format="id"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
final class ScreenCategory extends Model
{
    use SerializeToIso8601;

    protected $fillable = [
        'name',
        'status'
    ];

    /**
     * @return array{name: string, status: string}
     */
    public static function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:screen_categories,name',
            'status' => 'required|string|in:ACTIVE,INACTIVE'
        ];
    }

    /**
     * Get screens
     *
     * @return HasMany
     */
    public function screens()
    {
        return $this->hasMany(Screen::class);
    }
}
