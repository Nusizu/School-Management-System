<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo (password: password)
        $users = [
            ['name' => 'Administrator',      'email' => 'admin@school.test',   'role' => User::ROLE_ADMIN],
            ['name' => 'Ahmad Fauzi, S.Pd.', 'email' => 'teacher@school.test', 'role' => User::ROLE_TEACHER],
            ['name' => 'Budi Santoso',       'email' => 'student@school.test', 'role' => User::ROLE_STUDENT],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user + ['password' => 'password']
            );
        }

        // Urutan penting: kelas dan mapel dulu, lalu guru dan siswa
        $this->call([
            SchoolClassSeeder::class,
            SubjectSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
        ]);
    }
}
