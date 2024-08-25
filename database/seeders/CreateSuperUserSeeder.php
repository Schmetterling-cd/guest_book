<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateSuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superUser = User::create([
            'login' => env('SUPER_USER_LOGIN', 'admin'),
            'password' => Hash::make(env('SUPER_USER_PASSWORD', 'password')),
        ]);

        Role::create([
            'name' => 'super-user'
        ]);

        $superUser->assignRole('super-user');

    }
}
