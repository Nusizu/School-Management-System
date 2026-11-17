@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" class="flex flex-col items-center justify-between gap-3 sm:flex-row">
        <p class="text-sm text-gray-600">
            Menampilkan
            <span class="font-medium">{{ number_format($paginator->firstItem(), 0, ',', '.') }}</span>
            sampai
            <span class="font-medium">{{ number_format($paginator->lastItem(), 0, ',', '.') }}</span>
            dari
            <span class="font-medium">{{ number_format($paginator->total(), 0, ',', '.') }}</span>
            data
        </p>

        <ul class="inline-flex flex-wrap items-center gap-1 text-sm">
            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <li><span class="cursor-not-allowed rounded-md border border-gray-200 px-3 py-1.5 text-gray-400">Sebelumnya</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-100">Sebelumnya</a></li>
            @endif

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="px-3 py-1.5 text-gray-400">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span aria-current="page" class="rounded-md bg-gray-800 px-3 py-1.5 text-white">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-100">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-md border border-gray-300 px-3 py-1.5 text-gray-700 hover:bg-gray-100">Berikutnya</a></li>
            @else
                <li><span class="cursor-not-allowed rounded-md border border-gray-200 px-3 py-1.5 text-gray-400">Berikutnya</span></li>
            @endif
        </ul>
    </nav>
@endif
