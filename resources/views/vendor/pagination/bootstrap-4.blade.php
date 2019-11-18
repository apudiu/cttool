@if ($paginator->hasPages())
    <ul class="pagination justify-content-center" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            {{--First Page Link--}}
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="page-link" aria-hidden="true">&larrb;</span>
            </li>

            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span class="page-link" aria-hidden="true">&larr;</span>
            </li>
        @else
            {{--First Page Link--}}
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url(1) }}" rel="prev" aria-label="@lang('pagination.previous')">&larrb;</a>
            </li>

            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&larr;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rarr;</a>
            </li>

            {{--Last Page Link--}}
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" rel="next" aria-label="@lang('pagination.next')">&rarrb;</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="page-link" aria-hidden="true">&rarr;</span>
            </li>

            {{--Last Page Link--}}
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span class="page-link" aria-hidden="true">&rarrb;</span>
            </li>
        @endif
    </ul>
@endif
