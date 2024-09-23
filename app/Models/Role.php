<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    public function roleHasPermission(): HasMany
    {

        return $this->hasMany(RoleHasPermission::class, 'role_id', 'id');

    }

    protected static function boot()
    {

        parent::boot();

        static::deleting(function($role) {
            $role->roleHasPermission()->delete();
        });

    }

}
