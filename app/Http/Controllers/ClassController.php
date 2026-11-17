<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $classes = SchoolClass::query()
            ->active()
            ->withCount(['students as students_count' => fn ($q) => $q->where('archived', 0)])
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('classes.index', compact('classes', 'search'));
    }

    public function create()
    {
        return view('classes.add');
    }

    public function store(ClassRequest $request): RedirectResponse
    {
        SchoolClass::create($request->validated());

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit(SchoolClass $class)
    {
        abort_if($class->archived, 404);

        return view('classes.edit', ['class' => $class]);
    }

    public function update(ClassRequest $request, SchoolClass $class): RedirectResponse
    {
        abort_if($class->archived, 404);

        $class->update($request->validated());

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        abort_if($class->archived, 404);

        if ($class->students()->where('archived', 0)->exists()) {
            return redirect()->route('classes.index')
                ->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa aktif.');
        }

        $class->update(['archived' => 1]); // soft delete

        return redirect()->route('classes.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
