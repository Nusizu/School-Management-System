<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teacherUserId = User::where('email', 'teacher@school.test')->value('id');

        $teachers = [
            ['employee_number' => 'GR-001', 'name' => 'Ahmad Fauzi',    'subject' => 'MTK', 'phone' => '081234567801', 'user_id' => $teacherUserId],
            ['employee_number' => 'GR-002', 'name' => 'Dewi Lestari',   'subject' => 'BIN', 'phone' => '081234567802', 'user_id' => null],
            ['employee_number' => 'GR-003', 'name' => 'Rina Wulandari', 'subject' => 'BIG', 'phone' => '081234567803', 'user_id' => null],
            ['employee_number' => 'GR-004', 'name' => 'Hendra Wijaya',  'subject' => 'FIS', 'phone' => '081234567804', 'user_id' => null],
        ];

        foreach ($teachers as $t) {
            Teacher::updateOrCreate(
                ['employee_number' => $t['employee_number']],
                [
                    'name' => $t['name'],
                    'phone' => $t['phone'],
                    'user_id' => $t['user_id'],
                    'subject_id' => Subject::where('code', $t['subject'])->value('id'),
                ]
            );
        }
    }
}