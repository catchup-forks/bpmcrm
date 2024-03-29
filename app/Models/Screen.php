<?php

namespace App\Models;

use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Traits\SerializeToIso8601;

/**
 * Class Screen
 *
 * @package app\Models
 *
 * @property string id
 * @property string title
 * @property string description
 * @property array content
 * @property string label
 * @property Carbon type
 *
 *   @OA\Schema(
 *   schema="screensEditable",
 *   @OA\Property(property="id", type="string", format="id"),
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="title", type="string"),
 *   @OA\Property(property="type", type="string"),
 *   @OA\Property(property="description", type="string"),
 *   @OA\Property(property="config", type="string"),
 * ),
 * @OA\Schema(
 *   schema="screens",
 *   allOf={@OA\Schema(ref="#/components/schemas/screensEditable")},
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 *
 */
final class Screen extends Model
{
    use SerializeToIso8601;

    protected $casts = [
        'config' => 'array'
    ];

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
     * Validation rules
     *
     * @param $existing
     *
     * @return array{title: string, description: string, type: string}|array{title: Unique[]|string[]}
     */
    public static function rules($existing = null): array
    {
        $rules = [];
        if ($existing) {
            // ignore the unique rule for this id
            $rules['title'] = [
                'required',
                Rule::unique('screens')->ignore($existing->id, 'id')
            ];
        } else {
            $rules = [
                'title' => 'required|unique:screens,title',
                'description' => 'required',
                'type' => 'required'
            ];
        }
        return $rules;
    }
}
