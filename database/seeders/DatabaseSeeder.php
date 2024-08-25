<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{

    const PERMISSIONS_BASE_USER = ['addComment', 'updateComment', 'deleteComment', 'getComment', 'getCommentList'];
    const MANAGER_PERMISSIONS = ['getResponseToComment', 'addResponseToComment', 'updateResponseToComment', 'deleteResponseToComment', 'getCommentResponseList'];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create([
            'name' => 'manager'
        ]);

        $user = User::create([
            'name' => 'manager',
            'login' => 'manager',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('manager');
        $permissions = Permission::whereIn('name', array_merge(static::PERMISSIONS_BASE_USER, static::MANAGER_PERMISSIONS))->get();
        $user->syncPermissions($permissions);

        Role::create([
            'name' => 'basic-user'
        ]);

        $user = User::create([
            'name' => 'base-user',
            'login' => 'base_user',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('basic-user');
        $permissions = Permission::whereIn('name', static::PERMISSIONS_BASE_USER)->get();
        $user->syncPermissions($permissions);

    }
}
