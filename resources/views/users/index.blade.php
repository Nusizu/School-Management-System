@php $isAdmin = auth()->user()->isAdmin(); @endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Pengguna</h2>
            @if ($isAdmin)
                <a href="{{ route('users.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
                    + Tambah Pengguna
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert-dismissible mb-4 rounded-md bg-green-100 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-100 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @unless ($isAdmin)
                    <p class="mb-4 text-sm text-gray-500">Anda hanya dapat melihat data.</p>
                @endunless

                <form method="GET" action="{{ route('users.index') }}" class="mb-4 flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..."
                           class="w-full sm:w-80 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" autofocus>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-md hover:bg-gray-700">Cari</button>
                    @if ($search !== '')
                        <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:underline">Reset</a>
                    @endif
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase text-gray-500">
                            <tr>
                                <th class="px-4 py-3 text-right">No</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Role</th>
                                <th class="px-4 py-3">Data Terkait</th>
                                <th class="px-4 py-3">Terdaftar</th>
                                @if ($isAdmin)
                                    <th class="px-4 py-3">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-4 py-3 text-right">{{ number_format($users->firstItem() + $loop->index, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'teacher' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">{{ $user->role_label }}</span></td>
                                    <td class="px-4 py-3 text-gray-600">{{ $user->student?->name ?? $user->teacher?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ \App\Support\Format::date($user->created_at) }}</td>
                                    @if ($isAdmin)
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:underline">Edit</a>
                                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                      onsubmit="return confirm(@js('Hapus pengguna '.$user->name.'?'))">
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
                                    <td colspan="{{ $isAdmin ? 7 : 7 }}" class="px-4 py-6 text-center text-gray-500">Belum ada data pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
