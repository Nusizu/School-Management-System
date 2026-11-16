<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Administrator', 'email' => 'admin@school.test',   'role' => User::ROLE_ADMIN],
            ['name' => 'Ahmad Fauzi',   'email' => 'teacher@school.test', 'role' => User::ROLE_TEACHER],
            ['name' => 'Budi Santoso',  'email' => 'student@school.test', 'role' => User::ROLE_STUDENT],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user + ['password' => 'password']
            );
        }
    }
}