@if ($paginator->hasPages())
    <nav class="bc-pagination bc-pagination-simple" role="navigation" aria-label="Navegación de páginas">
        @if ($paginator->onFirstPage()) <span class="bc-page-button disabled">Anterior</span> @else <a class="bc-page-button" href="{{ $paginator->previousPageUrl() }}">Anterior</a> @endif
        @if ($paginator->hasMorePages()) <a class="bc-page-button" href="{{ $paginator->nextPageUrl() }}">Siguiente</a> @else <span class="bc-page-button disabled">Siguiente</span> @endif
    </nav>
@endif
