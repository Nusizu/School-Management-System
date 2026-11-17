@php
    $modules = [
        'users' => 'Data Pengguna',
        'students' => 'Data Siswa',
        'teachers' => 'Data Guru',
        'classes' => 'Data Kelas',
        'subjects' => 'Mata Pelajaran',
    ];

    $routeName = request()->route()?->getName();
    $crumbs = [['Dashboard', route('dashboard')]];

    if ($routeName && $routeName !== 'dashboard') {
        [$resource, $action] = array_pad(explode('.', $routeName, 2), 2, null);

        if (isset($modules[$resource])) {
            $crumbs[] = [$modules[$resource], route($resource.'.index')];

            if ($action === 'create') {
                $crumbs[] = ['Tambah', null];
            } elseif ($action === 'edit') {
                $record = request()->route(\Illuminate\Support\Str::singular($resource));
                $crumbs[] = [\Illuminate\Support\Str::limit(is_object($record) ? $record->name : 'Data', 30), null];
                $crumbs[] = ['Edit', null];
            }
        } elseif ($routeName === 'profile.edit') {
            $crumbs[] = ['Profil', null];
        }
    }
@endphp

<nav class="border-b border-gray-200 bg-gray-50" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-x-2 gap-y-1 px-4 py-2 text-sm text-gray-500 sm:px-6 lg:px-8">
        @foreach ($crumbs as $crumb)
            <li class="flex items-center gap-2">
                @if (! $loop->first)
                    <span class="text-gray-400">/</span>
                @endif

                @if ($crumb[1] && ! $loop->last)
                    <a href="{{ $crumb[1] }}" class="hover:text-gray-700 hover:underline">{{ $crumb[0] }}</a>
                @else
                    <span class="{{ $loop->last ? 'font-medium text-gray-700' : '' }}" @if ($loop->last) aria-current="page" @endif>{{ $crumb[0] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
