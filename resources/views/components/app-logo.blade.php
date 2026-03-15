@props([
    'sidebar' => false,
])

@php
    $logoUrl = asset('images/logo.png');
    $hasLogo = file_exists(public_path('images/logo.png'));
@endphp

@if($sidebar)
    <flux:sidebar.brand name="McWills" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-md bg-accent-content text-accent-foreground">
            @if($hasLogo)
                <img src="{{ $logoUrl }}" alt="McWills" class="size-full object-contain" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="McWills" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center overflow-hidden rounded-md bg-accent-content text-accent-foreground">
            @if($hasLogo)
                <img src="{{ $logoUrl }}" alt="McWills" class="size-full object-contain" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:brand>
@endif
