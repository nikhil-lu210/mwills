<nav class="mb-6 flex flex-wrap gap-2 border-b border-zinc-200 pb-4 dark:border-zinc-700" aria-label="{{ __('Settings sections') }}">
    <a
        href="{{ route('admin.settings.general') }}"
        wire:navigate
        @class([
            'inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium transition',
            'bg-zinc-200 text-zinc-900 dark:bg-zinc-700 dark:text-zinc-100' => request()->routeIs('admin.settings.general'),
            'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' => ! request()->routeIs('admin.settings.general'),
        ])
    >
        {{ __('General') }}
    </a>
    <a
        href="{{ route('admin.settings.analytics') }}"
        wire:navigate
        @class([
            'inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium transition',
            'bg-zinc-200 text-zinc-900 dark:bg-zinc-700 dark:text-zinc-100' => request()->routeIs('admin.settings.analytics'),
            'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800' => ! request()->routeIs('admin.settings.analytics'),
        ])
    >
        {{ __('Analytics') }}
    </a>
</nav>
