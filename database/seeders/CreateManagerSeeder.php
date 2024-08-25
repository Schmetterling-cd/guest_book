<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superUser = User::create([
            'login' => 'manager',
            'password' => Hash::make(env('SUPER_USER_PASSWORD', 'password')),
        ]);

        Role::create([
            'name' => 'manager'
        ]);

        $superUser->assignRole('super-user');

    }
}
