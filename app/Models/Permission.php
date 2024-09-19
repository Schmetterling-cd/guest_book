<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    public function roleHasPermission(): HasMany
    {

        return $this->hasMany(RoleHasPermission::class, 'permission_id', 'id');

    }

    public function modelHasPermission(): HasMany
    {

        return $this->hasMany(ModelHasPermission::class, 'permission_id', 'id');

    }

}
