@props([
    'currentPageOptionProperty' => 'tableRecordsPerPage',
    'extremeLinks' => false,
    'paginator',
    'pageOptions' => [],
])

@php
    $isSimple = ! $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator;

    $bag = $attributes->class([
        'fi-pagination',
        'fi-simple' => $isSimple,
    ]);
@endphp

{{-- Filament's `<x-filament::pagination>` ships extra affordances that
     Flux's free `<flux:pagination>` doesn't expose:
       - per-page `pageOptions` dropdown (`tableRecordsPerPage` etc.)
       - extreme-links jump-to-first / jump-to-last buttons
       - cursor-paginator special-cased buttons
     Disable the slug if any of these are critical — they fall back to
     Filament's native UI when this override isn't enabled. --}}
<x-flux::pagination :paginator="$paginator" :attributes="$bag" />
