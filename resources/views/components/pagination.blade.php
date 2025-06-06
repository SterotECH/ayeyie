@if ($paginator->hasPages())
    <nav class="flex items-center justify-between" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="flex flex-1 justify-between sm:hidden">
            {{-- Mobile Previous Button --}}
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex cursor-default items-center rounded-lg border border-gray-300 bg-zinc-50 px-4 py-2 text-sm font-medium text-gray-500 dark:bg-zinc-800">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <button
                    class="relative inline-flex items-center rounded-lg border border-gray-300 bg-zinc-50 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:bg-zinc-800"
                    wire:click="previousPage">
                    {!! __('pagination.previous') !!}
                </button>
            @endif

            {{-- Mobile Next Button --}}
            @if ($paginator->hasMorePages())
                <button
                    class="relative ml-3 inline-flex items-center rounded-lg border border-gray-300 bg-zinc-50 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:bg-zinc-800"
                    wire:click="nextPage">
                    {!! __('pagination.next') !!}
                </button>
            @else
                <span
                    class="relative ml-3 inline-flex cursor-default items-center rounded-lg border border-gray-300 bg-zinc-50 px-4 py-2 text-sm font-medium text-gray-500 dark:bg-zinc-800">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop Pagination --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    {!! __('Showing') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-lg shadow-sm rtl:flex-row-reverse">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="relative inline-flex cursor-default items-center rounded-l-lg border border-gray-300 bg-zinc-50 px-2 py-2 text-sm font-medium text-gray-500 dark:bg-zinc-800"
                                aria-hidden="true">
                                <x-heroicon-o-chevron-left class="h-5 w-5" />
                            </span>
                        </span>
                    @else
                        <button wire:click="previousPage" rel="prev"
                            class="relative inline-flex items-center rounded-l-lg border border-gray-300 bg-zinc-50 px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 dark:bg-gray-700 dark:bg-zinc-800"
                            aria-label="{{ __('pagination.previous') }}"
