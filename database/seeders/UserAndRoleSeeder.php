<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserAndRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            'administrator',
            'editor',
            'author',
            'contributor',
            'subscriber',
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create users and assign roles
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'role' => 'administrator',
            ],
            [
                'name' => 'Editor User',
                'email' => 'editor@gmail.com',
                'role' => 'editor',
            ],
            [
                'name' => 'Author User',
                'email' => 'author@gmail.com',
                'role' => 'author',
            ],
            [
                'name' => 'Contributor User',
                'email' => 'contributor@gmail.com',
                'role' => 'contributor',
            ],
            [
                'name' => 'Subscriber User',
                'email' => 'subscriber@gmail.com',
                'role' => 'subscriber',
            ],
        ];
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'), // default password
                ]
            );
            $user->syncRoles([$userData['role']]);
        }
    }
}
