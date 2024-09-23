<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateBasicUserSeeder extends Seeder
{

    const PERMISSIONS_BASE_USER = ['addComment', 'updateComment', 'deleteComment', 'getComment', 'getCommentList'];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Role::create([
            'name' => 'basic-user'
        ]);

        $user = User::create([
            'login' => 'base_user',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('basic-user');
        $permissions = Permission::whereIn('name', static::PERMISSIONS_BASE_USER)->get();
        $user->syncPermissions($permissions);


    }
}
