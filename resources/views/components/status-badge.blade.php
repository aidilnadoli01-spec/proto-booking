@props(['status' => '-'])

@php
    $value = strtolower((string) $status);
    $map = [
        'menunggu' => [
            'cls' => 'bg-amber-50 text-amber-800 ring-1 ring-inset ring-amber-200',
            'dot' => 'bg-amber-500',
        ],
        'dipanggil' => [
            'cls' => 'bg-sky-50 text-sky-800 ring-1 ring-inset ring-sky-200',
            'dot' => 'bg-sky-500',
        ],
        'selesai' => [
            'cls' => 'bg-emerald-50 text-emerald-800 ring-1 ring-inset ring-emerald-200',
            'dot' => 'bg-emerald-500',
        ],
        'batal' => [
            'cls' => 'bg-rose-50 text-rose-800 ring-1 ring-inset ring-rose-200',
            'dot' => 'bg-rose-500',
        ],
        'dibatalkan' => [
            'cls' => 'bg-rose-50 text-rose-800 ring-1 ring-inset ring-rose-200',
            'dot' => 'bg-rose-500',
        ],
    ];

    $cfg = $map[$value] ?? [
        'cls' => 'bg-slate-50 text-slate-700 ring-1 ring-inset ring-slate-200',
        'dot' => 'bg-slate-400',
    ];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {$cfg['cls']}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $cfg['dot'] }}"></span>
    {{ ucfirst($status) }}
</span>

