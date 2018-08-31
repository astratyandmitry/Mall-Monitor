@if ($paginator->hasPages())
    <div class="flex mt-8 items-center justify-center text-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="rounded mr-2 rounded-sm bg-grey-lighter text-grey-dark px-3 py-2 cursor-not-allowed no-underline">&laquo;</span>
        @else
            <a
                    class="rounded mr-2 border border-indigo rounded-sm bg-transparent px-3 py-2 text-indigo-dark hover:text-white hover:bg-indigo no-underline"
                    href="{{ $paginator->previousPageUrl() }}"
                    rel="prev"
            >
                &laquo;
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="rounded mr-2 rounded-sm bg-grey-lighter text-grey-dark px-3 py-2 cursor-not-allowed no-underline">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="rounded mr-2 rounded-sm bg-indigo px-3 py-2 text-white no-underline">{{ $page }}</span>
                    @else
                        <a class="rounded mr-2 border border-indigo rounded-sm bg-transparent px-3 py-2 text-indigo-dark hover:text-white hover:bg-indigo no-underline" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="rounded border border-indigo rounded-sm bg-transparent px-3 py-2 text-indigo-dark hover:text-white hover:bg-indigo no-underline" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        @else
            <span class="rounded rounded-sm bg-grey-lighter text-grey-dark px-4 py-2 cursor-not-allowed no-underline">&raquo;</span>
        @endif
    </div>
@endif