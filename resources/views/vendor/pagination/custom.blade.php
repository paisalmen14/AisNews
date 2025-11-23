@if ($paginator->hasPages())
    <div class="w-full flex items-center justify-center gap-3 pt-12 mb-10">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <p class="border border-slate-300 rounded-lg px-4 py-2 font-medium text-slate-300 cursor-not-allowed">&lt;</p>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="border border-slate-300 rounded-lg px-4 py-2 font-medium hover:bg-primary hover:border-none hover:text-white">&lt;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <p class="border border-slate-300 rounded-lg px-4 py-2 font-medium">{{ $element }}</p>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <p class="rounded-lg px-4 py-2 font-medium bg-primary text-white">{{ $page }}</p>
                    @else
                        <a href="{{ $url }}" class="border border-slate-300 rounded-lg px-4 py-2 font-medium hover:bg-primary hover:border-none hover:text-white">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="border border-slate-300 rounded-lg px-4 py-2 font-medium hover:bg-primary hover:border-none hover:text-white">&gt;</a>
        @else
            <p class="border border-slate-300 rounded-lg px-4 py-2 font-medium text-slate-300 cursor-not-allowed">&gt;</p>
        @endif
    </div>
@endif
