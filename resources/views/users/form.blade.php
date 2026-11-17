@php
    $input = 'mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm';
    $isSelf = $user && $user->id === auth()->id();
@endphp

<div class="space-y-5">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-600">*</span></label>
        <input id="name" type="text" name="name" value="{{ old('name', $user?->name) }}"
               placeholder="Contoh: Budi Santoso" class="{{ $input }}" autofocus required>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-600">*</span></label>
        <input id="email" type="email" name="email" value="{{ old('email', $user?->email) }}"
               placeholder="contoh@school.test" class="{{ $input }}" autocomplete="off" required>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <label for="role" class="block text-sm font-medium text-gray-700">Role <span class="text-red-600">*</span></label>
        <select id="role" name="role" class="{{ $input }} {{ $isSelf ? 'bg-gray-100' : '' }}" @disabled($isSelf) required>
            <option value="">-- Pilih role --</option>
            <option value="admin" @selected(old('role', $user?->role) === 'admin')>Administrator</option>
            <option value="teacher" @selected(old('role', $user?->role) === 'teacher')>Guru (Teacher)</option>
            <option value="student" @selected(old('role', $user?->role) === 'student')>Siswa (Student)</option>
        </select>
        @if ($isSelf)
            <p class="mt-1 text-xs text-gray-500">Role akun yang sedang Anda gunakan tidak dapat diubah.</p>
        @endif
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
                {{ $user ? 'Password Baru' : 'Password' }}
                @unless ($user) <span class="text-red-600">*</span> @endunless
            </label>
            <input id="password" type="password" name="password" placeholder="{{ $user ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                   class="{{ $input }}" autocomplete="new-password" @required(! $user)>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                Konfirmasi Password
                @unless ($user) <span class="text-red-600">*</span> @endunless
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password"
                   class="{{ $input }}" autocomplete="new-password" @required(! $user)>
        </div>
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
            Simpan
        </button>
        <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
    </div>
</div>
