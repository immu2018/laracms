<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        // Assign 'administrator' role to the first user if not assigned
        $admin = \App\Models\User::first();
        if ($admin && !$admin->hasRole('administrator')) {
            $admin->assignRole('administrator');
        }
    }
}
