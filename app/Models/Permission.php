<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    public static function byGuardName($name)
    {
        try {
            return self::where('guard_name', $name)->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException($name . " permission does not exist");
        }
    }
}
