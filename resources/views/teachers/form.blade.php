@php $input = 'mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm'; @endphp

<div class="space-y-5">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-600">*</span></label>
        <input id="name" type="text" name="name" value="{{ old('name', $teacher?->name) }}"
               placeholder="Contoh: Ahmad Fauzi" class="{{ $input }}" autofocus required>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <label for="subject_id" class="block text-sm font-medium text-gray-700">Mata Pelajaran <span class="text-red-600">*</span></label>
        <select id="subject_id" name="subject_id" class="{{ $input }}" required>
            <option value="">-- Pilih mata pelajaran --</option>
            @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}" @selected(old('subject_id', $teacher?->subject_id) == $subject->id)>{{ $subject->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="employee_number" class="block text-sm font-medium text-gray-700">Nomor Induk</label>
            <input id="employee_number" type="text" name="employee_number" value="{{ old('employee_number', $teacher?->employee_number) }}"
                   placeholder="Contoh: GR-005" class="{{ $input }}">
            <x-input-error :messages="$errors->get('employee_number')" class="mt-2" />
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone', $teacher?->phone) }}"
                   placeholder="Contoh: 081234567890" class="{{ $input }}">
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
    </div>

    <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
        <textarea id="address" name="address" rows="3" placeholder="Alamat lengkap guru"
                  class="{{ $input }}">{{ old('address', $teacher?->address) }}</textarea>
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div>
        <label for="user_id" class="block text-sm font-medium text-gray-700">Akun Pengguna (opsional)</label>
        <select id="user_id" name="user_id" class="{{ $input }}">
            <option value="">-- Tanpa akun --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected(old('user_id', $teacher?->user_id) == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
            Simpan
        </button>
        <a href="{{ route('teachers.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
    </div>
</div>
