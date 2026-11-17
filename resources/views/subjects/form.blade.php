@php $input = 'mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm'; @endphp

<div class="space-y-5">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
            <label for="code" class="block text-sm font-medium text-gray-700">Kode <span class="text-red-600">*</span></label>
            <input id="code" type="text" name="code" value="{{ old('code', $subject?->code) }}"
                   placeholder="Contoh: MTK" class="{{ $input }} uppercase" autofocus required>
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>
        <div class="sm:col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Mata Pelajaran <span class="text-red-600">*</span></label>
            <input id="name" type="text" name="name" value="{{ old('name', $subject?->name) }}"
                   placeholder="Contoh: Matematika" class="{{ $input }}" required>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Keterangan</label>
        <textarea id="description" name="description" rows="3" placeholder="Keterangan mata pelajaran (opsional)"
                  class="{{ $input }}">{{ old('description', $subject?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
            Simpan
        </button>
        <a href="{{ route('subjects.index') }}" class="text-sm text-gray-600 hover:underline">Batal</a>
    </div>
</div>
