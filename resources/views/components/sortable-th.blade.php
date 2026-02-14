@props(['field', 'label', 'currentSort', 'align' => 'left'])

@php
$isSorted = isset($currentSort['field']) && $currentSort['field'] === $field;
$direction = $isSorted && ($currentSort['direction'] ?? 'desc') === 'desc' ? 'asc' : 'desc';

// We want to keep existing query params but update sort_by and sort_direction
$url = request()->fullUrlWithQuery([
'sort_by' => $field,
'sort_direction' => $direction
]);

$justifyClass = match($align) {
'right' => 'justify-end',
'center' => 'justify-center',
default => 'justify-start',
};
@endphp

<th {{ $attributes->merge(['class' => 'px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400
    cursor-pointer hover:text-indigo-500 transition-colors group select-none']) }}
    onclick="window.location.href='{{ $url }}'">
    <div class="flex items-center gap-1 {{ $justifyClass }}">
        {{ $label }}
        <span
            class="inline-block transition-transform duration-200 {{ $isSorted ? 'opacity-100 text-indigo-500' : 'opacity-0 group-hover:opacity-30' }}">
            @if($isSorted && ($currentSort['direction'] ?? 'desc') === 'asc')
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" />
            </svg>
            @else
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
            </svg>
            @endif
        </span>
    </div>
</th>