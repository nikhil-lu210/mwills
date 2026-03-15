@props([
    'sidebar' => false,
])

@php
    $logoUrl = asset('assets/images/logo.png');
    $hasLogo = file_exists(public_path('assets/images/logo.png'));
@endphp

@if($sidebar)
    <flux:sidebar.brand name="McWills" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-7 min-w-[1.75rem] max-w-10 shrink-0 items-center justify-center overflow-hidden rounded-md bg-accent-content text-accent-foreground sm:size-8">
            @if($hasLogo)
                <img src="{{ $logoUrl }}" alt="McWills" class="max-h-full max-w-full object-contain" width="32" height="32" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="McWills" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-7 min-w-[1.75rem] max-w-10 shrink-0 items-center justify-center overflow-hidden rounded-md bg-accent-content text-accent-foreground sm:size-8">
            @if($hasLogo)
                <img src="{{ $logoUrl }}" alt="McWills" class="max-h-full max-w-full object-contain" width="32" height="32" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:brand>
@endif
