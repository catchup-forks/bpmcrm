<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class Permission extends Model
{

    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    static public function byGuardName($name)
    {
        try {
            return self::where('guard_name', $name)->firstOrFail();
        } catch(ModelNotFoundException) {
            throw new ModelNotFoundException($name . " permission does not exist");
        }
    }
}
