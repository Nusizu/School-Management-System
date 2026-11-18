@props(['code', 'title', 'message'])

<x-guest-layout>
    <div class="text-center">
        <p class="text-5xl font-bold text-gray-800">{{ $code }}</p>
        <h1 class="mt-3 text-lg font-semibold text-gray-900">{{ $title }}</h1>
        <p class="mt-2 text-sm text-gray-600">{{ $message }}</p>
        <a href="{{ url('/') }}"
           class="mt-6 inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
            Kembali ke Beranda
        </a>
    </div>
</x-guest-layout>
