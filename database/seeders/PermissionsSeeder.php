<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Permission::create(['name' => 'getResponseToComment']);
        Permission::create(['name' => 'addResponseToComment']);
        Permission::create(['name' => 'updateResponseToComment']);
        Permission::create(['name' => 'deleteResponseToComment']);
        Permission::create(['name' => 'getCommentResponseList']);

        Permission::create(['name' => 'addComment']);
        Permission::create(['name' => 'updateComment']);
        Permission::create(['name' => 'deleteComment']);
        Permission::create(['name' => 'getComment']);
        Permission::create(['name' => 'getCommentList']);

        Permission::create(['name' => 'getRoleList']);
        Permission::create(['name' => 'getRole']);
        Permission::create(['name' => 'addRole']);
        Permission::create(['name' => 'updateRole']);
        Permission::create(['name' => 'deleteRole']);

        Permission::create(['name' => 'getPermissionList']);
        Permission::create(['name' => 'addPermission']);
        Permission::create(['name' => 'updatePermission']);
        Permission::create(['name' => 'getPermission']);

        Permission::create(['name' => 'setUserRole']);

        $role = Role::where('name', '=', 'super-user')->first();
        $role->syncPermissions(Permission::all());

    }
}
