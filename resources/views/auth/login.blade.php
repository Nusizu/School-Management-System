<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-semibold text-gray-800">Masuk ke Sistem</h1>
        <p class="mt-1 text-sm text-gray-500">Silakan login dengan akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email">
                Email <span class="text-red-600">*</span>
            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" placeholder="contoh@school.test"
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password">
                Password <span class="text-red-600">*</span>
            </x-input-label>
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                          placeholder="Masukkan password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Ingat saya -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                Masuk
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>