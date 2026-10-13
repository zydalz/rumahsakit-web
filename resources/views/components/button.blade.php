
@props([
    'variant' => 'primary',
    'type' => 'button',
    'disabled' => false,
])

<button
    type="{{ $type }}"
    @disabled($disabled)
    {{ $attributes->class([
        'inline-flex items-center justify-center gap-2 rounded-lg px-5 py-2.5 text-sm font-semibold transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50' => true,

        'bg-oasis-blue text-white hover:bg-oasis-navy focus:ring-oasis-blue'
            => $variant === 'primary',

        'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 focus:ring-slate-400'
            => $variant === 'secondary',

        'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500'
            => $variant === 'danger',
    ]) }}
>
    {{ $slot }}
</button>
