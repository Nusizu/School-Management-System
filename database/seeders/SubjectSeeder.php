<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['code' => 'MTK', 'name' => 'Matematika'],
            ['code' => 'BIN', 'name' => 'Bahasa Indonesia'],
            ['code' => 'BIG', 'name' => 'Bahasa Inggris'],
            ['code' => 'FIS', 'name' => 'Fisika'],
            ['code' => 'BIO', 'name' => 'Biologi'],
            ['code' => 'SEJ', 'name' => 'Sejarah'],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(['code' => $subject['code']], ['name' => $subject['name']]);
        }
    }
}