<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $teachers = Teacher::query()
            ->join('tbl_subjects', 'tbl_subjects.id', '=', 'tbl_teachers.subject_id')
            ->where('tbl_teachers.archived', 0)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tbl_teachers.name', 'like', "%{$search}%")
                      ->orWhere('tbl_teachers.employee_number', 'like', "%{$search}%");
                });
            })
            ->select('tbl_teachers.*', 'tbl_subjects.name as subject_name')
            ->orderByDesc('tbl_teachers.id')
            ->paginate(10)
            ->withQueryString();

        return view('teachers.index', compact('teachers', 'search'));
    }

    public function create()
    {
        return view('teachers.add', [
            'subjects' => Subject::active()->orderBy('name')->get(),
            'users' => $this->availableUsers(),
        ]);
    }

    public function store(TeacherRequest $request): RedirectResponse
    {
        Teacher::create($request->validated());

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        abort_if($teacher->archived, 404);

        return view('teachers.edit', [
            'teacher' => $teacher,
            'subjects' => Subject::active()->orderBy('name')->get(),
            'users' => $this->availableUsers($teacher->user_id),
        ]);
    }

    public function update(TeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        abort_if($teacher->archived, 404);

        $teacher->update($request->validated());

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        abort_if($teacher->archived, 404);

        $teacher->update(['archived' => 1]); // soft delete

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }

    /** Akun role teacher yang belum dipakai guru lain (plus akun guru yang sedang diedit). */
    private function availableUsers(?int $currentUserId = null)
    {
        $taken = Teacher::whereNotNull('user_id')->pluck('user_id');

        return User::where('role', User::ROLE_TEACHER)
            ->where('archived', 0)
            ->where(function ($q) use ($taken, $currentUserId) {
                $q->whereNotIn('id', $taken);
                if ($currentUserId) {
                    $q->orWhere('id', $currentUserId);
                }
            })
            ->orderBy('name')
            ->get();
    }
}
