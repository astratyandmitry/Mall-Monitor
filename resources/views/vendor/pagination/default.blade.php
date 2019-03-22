@if ($paginator->hasPages())
    <div class="pagination">
        <ul class="pagination-list">
            @if ($paginator->onFirstPage())
                <li class="pagination-list-item is-action is-disabled">
                    <span class="pagination-list-item-value">&laquo;</span>
                </li>
            @else
                <li class="pagination-list-item is-action">
                    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-list-item-link" rel="prev">&laquo;</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="pagination-list-item is-disabled">
                        <span class="pagination-list-item-value">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-list-item is-active">
                                <span class="pagination-list-item-value">{{ $page }}</span>
                            </li>
                        @else
                            <li class="pagination-list-item">
                                <a href="{{ $url }}" class="pagination-list-item-link">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="pagination-list-item is-action">
                    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-list-item-link" rel="next">&raquo;</a>
                </li>
            @else
                <li class="pagination-list-item is-action is-disabled">
                    <span class="pagination-list-item-value">&raquo;</span>
                </li>
            @endif
        </ul>
    </div>
@endif
