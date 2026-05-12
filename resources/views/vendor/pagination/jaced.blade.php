@if ($paginator->hasPages())
    <nav class="pagination" style="display: flex; justify-content: center; gap: 8px; margin-top: 64px;">
        @if ($paginator->onFirstPage())
            <button class="page-btn" disabled style="opacity: 0.4; cursor: not-allowed;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-btn" style="border: none; background: transparent;">...</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="page-btn active">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        @else
            <button class="page-btn" disabled style="opacity: 0.4; cursor: not-allowed;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </button>
        @endif
    </nav>
@endif