@php
    $input = 'mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm';
@endphp

<div class="space-y-5">
    @if ($student)
        <div>
            <label class="block text-sm font-medium text-gray-700">Kode Siswa</label>
            <input type="text" value="{{ $student->student_code }}" class="{{ $input }} bg-gray-100" disabled>
        </div>
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-600">*</span></label>
        <input id="name" type="text" name="name" value="{{ old('name', $student?->name) }}"
               placeholder="Contoh: Budi Santoso" class="{{ $input }}" autofocus required>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <label for="class_id" class="block text-sm font-medium text-gray-700">Kelas <span class="text-red-600">*</span></label>
        <select id="class_id" name="class_id" class="{{ $input }}" required>
            <option value="">-- Pilih kelas --</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}" @selected(old('class_id', $student?->class_id) == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
            <input id="birth_date" type="date" name="birth_date"
                   value="{{ old('birth_date', $student?->birth_date?->format('Y-m-d')) }}" class="{{ $input }}">
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone', $student?->phone) }}"
                   placeholder="Contoh: 081234567890" class="{{ $input }}">
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
    </div>

    <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
        <textarea id="address" name="address" rows="3" placeholder="Alamat lengkap siswa"
                  class="{{ $input }}">{{ old('address', $student?->address) }}</textarea>
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-600">*</span></label>
            <select id="status" name="status" class="{{ $input }}" required>
                <option value="active" @selected(old('status', $student?->status ?? 'active') === 'active')>Aktif</option>
                <option value="transferred" @selected(old('status', $student?->status) === 'transferred')>Pindah</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
        <div>
            <label for="user_id" class="block text-sm font-medium text-gray-700">Akun Pengguna (opsional)</label>
            <select id="user_id" name="user_id" class="{{ $input }}">
                <option value="">-- Tanpa akun --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id', $student?->user_id) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
        </div>
    </div>

    <div>
        <label for="photo" class="block text-sm font-medium text-gray-700">Foto (JPG/PNG, maks. 2 MB)</label>
        @if ($student?->img_url)
            <img src="{{ asset($student->img_url) }}" alt="Foto {{ $student->name }}" class="mt-2 mb-2 h-20 w-20 rounded-full object-cover">
        @endif
        <input id="photo" type="file" name="photo" accept=".jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-700">
        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
            Simpan
        </button>
        <a href="{{ route('students.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
    </div>
</div>