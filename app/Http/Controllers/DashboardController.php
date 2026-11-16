<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Student: hanya melihat data pribadinya
        if ($user->isStudent()) {
            $student = Student::query()
                ->join('tbl_classes', 'tbl_classes.id', '=', 'tbl_students.class_id')
                ->where('tbl_students.user_id', $user->id)
                ->where('tbl_students.archived', 0)
                ->select('tbl_students.*', 'tbl_classes.name as class_name')
                ->first();

            return view('dashboard.student', compact('user', 'student'));
        }

        // Admin dan Teacher: ringkasan data
        $counts = [
            'students' => Student::active()->count(),
            'teachers' => Teacher::active()->count(),
            'classes' => SchoolClass::active()->count(),
            'subjects' => Subject::active()->count(),
        ];

        if ($user->isAdmin()) {
            $counts['users'] = User::where('archived', 0)->count();
        }

        $recentStudents = Student::query()
            ->join('tbl_classes', 'tbl_classes.id', '=', 'tbl_students.class_id')
            ->where('tbl_students.archived', 0)
            ->select('tbl_students.*', 'tbl_classes.name as class_name')
            ->orderByDesc('tbl_students.id')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('user', 'counts', 'recentStudents'));
    }
}