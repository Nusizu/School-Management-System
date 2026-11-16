<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ringkasan Sistem</h2>
            @if ($user->isAdmin())
                <a href="{{ route('students.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
                    + Tambah Siswa
                </a>
            @endif
        </div>
    </x-slot>

    @php
        $cards = collect([
            ['label' => 'Pengguna (Users)', 'value' => $counts['users'] ?? null, 'border' => 'border-indigo-500'],
            ['label' => 'Siswa (Students)', 'value' => $counts['students'], 'border' => 'border-blue-500'],
            ['label' => 'Guru (Teachers)', 'value' => $counts['teachers'], 'border' => 'border-green-500'],
            ['label' => 'Kelas (Classes)', 'value' => $counts['classes'], 'border' => 'border-yellow-500'],
            ['label' => 'Mapel (Subjects)', 'value' => $counts['subjects'], 'border' => 'border-red-500'],
        ])->filter(fn ($card) => $card['value'] !== null);

        $gridCols = $user->isAdmin() ? 'lg:grid-cols-5' : 'lg:grid-cols-4';
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="text-gray-700">
                Selamat datang, <span class="font-semibold">{{ $user->name }}</span>
                <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-700">{{ $user->role_label }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridCols }} gap-4">
                @foreach ($cards as $card)
                    <div class="bg-white shadow-sm rounded-lg border-l-4 {{ $card['border'] }} p-5">
                        <p class="text-xs font-semibold uppercase text-gray-500">{{ $card['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold text-gray-800">{{ number_format($card['value'], 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Data Siswa Terbaru</h3>
                    <a href="{{ route('students.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Nama Siswa</th>
                                <th class="px-6 py-3">Kelas</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($recentStudents as $student)
                                <tr>
                                    <td class="px-6 py-3 text-gray-600">{{ $student->student_code }}</td>
                                    <td class="px-6 py-3 font-medium text-gray-900">{{ $student->name }}</td>
                                    <td class="px-6 py-3">{{ $student->class_name }}</td>
                                    <td class="px-6 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $student->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-gray-500">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($user->isTeacher())
                <p class="text-sm text-gray-500">Anda login sebagai guru. Data pada sistem hanya dapat dilihat, tidak dapat diubah.</p>
            @endif
        </div>
    </div>
</x-app-layout>