<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Siswa</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="text-gray-700">
                Selamat datang, <span class="font-semibold">{{ $user->name }}</span>
                <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-200 text-gray-700">{{ $user->role_label }}</span>
            </div>

            @if ($student)
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center gap-4 mb-6">
                        @if ($student->img_url)
                            <img src="{{ asset($student->img_url) }}" alt="Foto {{ $student->name }}"
                                 class="h-16 w-16 rounded-full object-cover">
                        @else
                            <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-xl text-gray-500 font-semibold">
                                {{ mb_strtoupper(mb_substr($student->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-lg font-semibold text-gray-900">{{ $student->name }}</p>
                            <p class="text-sm text-gray-500">{{ $student->student_code }}</p>
                        </div>
                    </div>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Kelas</dt>
                            <dd class="font-medium text-gray-900">{{ $student->class_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Status</dt>
                            <dd>
                                <span class="px-2 py-1 text-xs rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $student->status_label }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Tanggal Lahir</dt>
                            <dd class="font-medium text-gray-900">{{ $student->birth_date_formatted }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Telepon</dt>
                            <dd class="font-medium text-gray-900">{{ $student->phone ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500">Alamat</dt>
                            <dd class="font-medium text-gray-900">{{ $student->address ?: '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <p class="text-sm text-gray-500">Data ini hanya dapat dilihat. Hubungi administrator jika ada data yang perlu diperbaiki.</p>
            @else
                <div class="rounded-md bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                    Akun Anda belum ditautkan ke data siswa. Hubungi administrator.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>