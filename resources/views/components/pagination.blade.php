@if ($paginator->hasPages())
    <nav class="bc-pagination" role="navigation" aria-label="Navegación de páginas">
        <div class="bc-pagination-summary">
            Mostrando <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
            de <strong>{{ $paginator->total() }}</strong>
        </div>

        <div class="bc-pagination-controls">
            @if ($paginator->onFirstPage())
                <span class="bc-page-button disabled" aria-disabled="true"><x-icon name="chevron-left" /><span class="sr-only">Anterior</span></span>
            @else
                <a class="bc-page-button" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior"><x-icon name="chevron-left" /></a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="bc-page-dots">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="bc-page-button active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="bc-page-button" href="{{ $url }}" aria-label="Ir a la página {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="bc-page-button" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Página siguiente"><x-icon name="chevron-right" /></a>
            @else
                <span class="bc-page-button disabled" aria-disabled="true"><x-icon name="chevron-right" /><span class="sr-only">Siguiente</span></span>
            @endif
        </div>
    </nav>
@endif
