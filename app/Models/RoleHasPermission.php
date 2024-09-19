<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    public function role(): HasOne
    {

        return $this->hasOne(Role::class, 'id', 'role_id');

    }

    public function permission(): HasOne
    {

        return $this->hasOne(Permission::class, 'id', 'permission_id');

    }

}
