<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    private const UPLOAD_DIR = 'uploads/students';

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $students = Student::query()
            ->join('tbl_classes', 'tbl_classes.id', '=', 'tbl_students.class_id')
            ->where('tbl_students.archived', 0)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tbl_students.name', 'like', "%{$search}%")
                      ->orWhere('tbl_students.student_code', 'like', "%{$search}%");
                });
            })
            ->select('tbl_students.*', 'tbl_classes.name as class_name')
            ->orderByDesc('tbl_students.id')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', compact('students', 'search'));
    }

    public function create()
    {
        return view('students.add', [
            'classes' => SchoolClass::active()->orderBy('name')->get(),
            'users' => $this->availableUsers(),
        ]);
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('photo');
        $data['student_code'] = 'STD-'.str_pad((string) ((Student::max('id') ?? 0) + 1), 3, '0', STR_PAD_LEFT);

        if ($request->hasFile('photo')) {
            $data['img_url'] = $this->uploadPhoto($request->file('photo'));
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        abort_if($student->archived, 404);

        return view('students.edit', [
            'student' => $student,
            'classes' => SchoolClass::active()->orderBy('name')->get(),
            'users' => $this->availableUsers($student->user_id),
        ]);
    }

    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        abort_if($student->archived, 404);

        $data = $request->safe()->except('photo');

        if ($request->hasFile('photo')) {
            $this->deletePhoto($student->img_url);
            $data['img_url'] = $this->uploadPhoto($request->file('photo'));
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        abort_if($student->archived, 404);

        $student->update(['archived' => 1]); // soft delete

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    /** Akun role student yang belum dipakai siswa lain (plus akun siswa yang sedang diedit). */
    private function availableUsers(?int $currentUserId = null)
    {
        $taken = Student::whereNotNull('user_id')->pluck('user_id');

        return User::where('role', User::ROLE_STUDENT)
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

    /** Simpan ke public/uploads/students dengan format nama YmdHis-nama_file. */
    private function uploadPhoto(UploadedFile $file): string
    {
        File::ensureDirectoryExists(public_path(self::UPLOAD_DIR));

        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'photo';
        $filename = now()->format('YmdHis').'-'.$name.'.'.$file->extension();

        $file->move(public_path(self::UPLOAD_DIR), $filename);

        return self::UPLOAD_DIR.'/'.$filename;
    }

    private function deletePhoto(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}