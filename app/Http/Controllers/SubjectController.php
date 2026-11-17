<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $subjects = Subject::query()
            ->active()
            ->withCount(['teachers as teachers_count' => fn ($q) => $q->where('archived', 0)])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('subjects.index', compact('subjects', 'search'));
    }

    public function create()
    {
        return view('subjects.add');
    }

    public function store(SubjectRequest $request): RedirectResponse
    {
        Subject::create($request->validated());

        return redirect()->route('subjects.index')->with('success', 'Data mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject)
    {
        abort_if($subject->archived, 404);

        return view('subjects.edit', ['subject' => $subject]);
    }

    public function update(SubjectRequest $request, Subject $subject): RedirectResponse
    {
        abort_if($subject->archived, 404);

        $subject->update($request->validated());

        return redirect()->route('subjects.index')->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        abort_if($subject->archived, 404);

        if ($subject->teachers()->where('archived', 0)->exists()) {
            return redirect()->route('subjects.index')
                ->with('error', 'Mata pelajaran tidak dapat dihapus karena masih diampu guru aktif.');
        }

        $subject->update(['archived' => 1]); // soft delete

        return redirect()->route('subjects.index')->with('success', 'Data mata pelajaran berhasil dihapus.');
    }
}
