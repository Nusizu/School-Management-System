<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $studentUserId = User::where('email', 'student@school.test')->value('id');

        $students = [
            ['student_code' => 'STD-001', 'name' => 'Budi Santoso', 'class' => '10 IPA 1', 'status' => 'active',      'birth_date' => '2010-03-14', 'user_id' => $studentUserId],
            ['student_code' => 'STD-002', 'name' => 'Siti Aminah',  'class' => '11 IPS 2', 'status' => 'active',      'birth_date' => '2009-07-02', 'user_id' => null],
            ['student_code' => 'STD-003', 'name' => 'Andi Saputra', 'class' => '12 IPA 3', 'status' => 'transferred', 'birth_date' => '2008-11-21', 'user_id' => null],
        ];

        foreach ($students as $s) {
            Student::updateOrCreate(
                ['student_code' => $s['student_code']],
                [
                    'name' => $s['name'],
                    'class_id' => SchoolClass::where('name', $s['class'])->value('id'),
                    'status' => $s['status'],
                    'birth_date' => $s['birth_date'],
                    'user_id' => $s['user_id'],
                ]
            );
        }
    }
}