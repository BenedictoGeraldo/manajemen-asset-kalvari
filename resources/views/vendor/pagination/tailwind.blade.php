@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-1">
        {{-- Previous Arrow --}}
        @if ($paginator->onFirstPage())
            <span class="px-2 py-1.5 text-sm text-gray-300 cursor-not-allowed" aria-disabled="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="px-2 py-1.5 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @php
            $window = 2;
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();
            $elements = [];
            
            // Always show first page
            $elements[] = 1;
            
            // Calculate window start/end around current
            $start = max(2, $current - $window);
            $end = min($last - 1, $current + $window);
            
            // Add dots after 1 if needed
            if ($start > 2) {
                $elements[] = '...';
            }
            
            // Add window pages
            for ($i = $start; $i <= $end; $i++) {
                $elements[] = $i;
            }
            
            // Add dots before last if needed
            if ($end < $last - 1) {
                $elements[] = '...';
            }
            
            // Always show last page if more than 1
            if ($last > 1) {
                $elements[] = $last;
            }
        @endphp

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 py-1 text-sm text-gray-400">{{ $element }}</span>
            @elseif ($element == $current)
                <span class="px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded" aria-current="page">{{ $element }}</span>
            @else
                <a href="{{ $paginator->url($element) }}"
                   class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded transition-colors">{{ $element }}</a>
            @endif
        @endforeach

        {{-- Next Arrow --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="px-2 py-1.5 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="px-2 py-1.5 text-sm text-gray-300 cursor-not-allowed" aria-disabled="true">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </nav>
@endif
