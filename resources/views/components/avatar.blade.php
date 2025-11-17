@props([
    'user',
    'size' => 'md',
    'showLevel' => false,
    'class' => ''
])

@php
$sizes = [
    'xs' => 'w-7 h-7',
    'sm' => 'w-9 h-9',
    'md' => 'w-12 h-12',
    'lg' => 'w-16 h-16',
    'xl' => 'w-20 h-20',
    '2xl' => 'w-28 h-28',
];
$sizeClass = $sizes[$size] ?? $sizes['md'];

$ringSize = [
    'xs' => 'ring-1',
    'sm' => 'ring-2',
    'md' => 'ring-2',
    'lg' => 'ring-2',
    'xl' => 'ring-3',
    '2xl' => 'ring-4',
];
$ringSizeClass = $ringSize[$size] ?? $ringSize['md'];
@endphp

<div class="relative inline-flex {{ $class }}">
    <div class="{{ $sizeClass }} rounded-full overflow-hidden bg-gradient-to-br from-purple-400 via-purple-500 to-indigo-600 flex items-center justify-center shadow-md {{ $ringSizeClass }} ring-white">
        <img src="{{ $user->avatar_url }}" 
             alt="{{ $user->name }}"
             class="w-full h-full object-cover"
             loading="lazy"
             onerror="this.onerror=null; this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-white font-bold text-lg\'>{{ $user->iniciales }}</span>';">
    </div>
    @if($showLevel)
        <span class="absolute -bottom-1 -right-1 {{ $size === '2xl' ? 'text-2xl' : ($size === 'xl' ? 'text-xl' : 'text-base') }} leading-none bg-white rounded-full shadow-md border-2 border-white {{ $size === 'xs' || $size === 'sm' ? 'p-0.5' : 'p-1' }}">
            {{ $user->nivel_emoji }}
        </span>
    @endif
</div>
