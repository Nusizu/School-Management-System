@php $isAdmin = auth()->user()->isAdmin(); @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Siswa</h2>
            @if ($isAdmin)
                <a href="{{ route('students.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
                    + Tambah Siswa
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-100 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @unless ($isAdmin)
                    <p class="mb-4 text-sm text-gray-500">Anda hanya dapat melihat data.</p>
                @endunless

                <form method="GET" action="{{ route('students.index') }}" class="mb-4 flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau kode siswa..."
                           class="w-full sm:w-80 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" autofocus>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">Cari</button>
                    @if ($search !== '')
                        <a href="{{ route('students.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:underline">Reset</a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Foto</th>
                                <th class="px-4 py-3">Kode</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Kelas</th>
                                <th class="px-4 py-3">Tanggal Lahir</th>
                                <th class="px-4 py-3">Status</th>
                                @if ($isAdmin)
                                    <th class="px-4 py-3">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($students as $student)
                                <tr>
                                    <td class="px-4 py-3">{{ $students->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3">
                                        @if ($student->img_url)
                                            <img src="{{ asset($student->img_url) }}" alt="Foto {{ $student->name }}"
                                                 class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-semibold">
                                                {{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $student->student_code }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $student->name }}</td>
                                    <td class="px-4 py-3">{{ $student->class_name }}</td>
                                    <td class="px-4 py-3">{{ $student->birth_date_formatted }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $student->status_label }}
                                        </span>
                                    </td>
                                    @if ($isAdmin)
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('students.edit', $student) }}" class="text-indigo-600 hover:underline">Edit</a>
                                                <form method="POST" action="{{ route('students.destroy', $student) }}"
                                                      onsubmit="return confirm('Hapus data siswa {{ $student->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isAdmin ? 8 : 7 }}" class="px-4 py-6 text-center text-gray-500">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $students->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>