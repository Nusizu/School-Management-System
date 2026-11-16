<x-guest-layout>
    <div class="text-center">
        <p class="text-5xl font-bold text-gray-800">403</p>
        <p class="mt-3 text-gray-600">Anda tidak memiliki akses ke halaman ini.</p>
        <a href="{{ route('dashboard') }}"
           class="mt-6 inline-flex items-center px-4 py-2 bg-gray-800 text-white text-xs font-semibold uppercase tracking-widest rounded-md hover:bg-gray-700">
            Kembali ke Dashboard
        </a>
    </div>
</x-guest-layout>