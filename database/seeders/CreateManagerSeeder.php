<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateManagerSeeder extends Seeder
{

    const MANAGER_PERMISSIONS = ['getResponseToComment', 'addResponseToComment', 'updateResponseToComment', 'deleteResponseToComment', 'getCommentResponseList'];
    const PERMISSIONS_BASE_USER = ['addComment', 'updateComment', 'deleteComment', 'getComment', 'getCommentList'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Role::create([
            'name' => 'manager'
        ]);

        $user = User::create([
            'login' => 'manager',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('manager');
        $permissions = Permission::whereIn('name', array_merge(static::PERMISSIONS_BASE_USER, static::MANAGER_PERMISSIONS))->get();
        $user->syncPermissions($permissions);

    }
}
