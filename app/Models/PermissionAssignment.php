<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class PermissionAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'permission_id',
        'assignable_id',
        'assignable_type',
    ];

    public function assignable()
    {
        return $this->morphTo(null, null, 'assignable_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
