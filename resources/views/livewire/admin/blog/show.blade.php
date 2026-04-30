<div
    class="mx-auto w-full min-w-0 max-w-5xl space-y-8"
    wire:key="admin-post-view-{{ $post->id }}"
>
    {{-- Toolbar --}}
    <div class="flex min-w-0 flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:link
            :href="route('admin.posts.index')"
            wire:navigate
            variant="ghost"
            class="inline-flex w-fit shrink-0 items-center gap-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
        >
            <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Back to posts') }}
        </flux:link>

        <div class="flex min-w-0 flex-wrap items-center gap-2">
            <flux:button variant="primary" :href="route('admin.posts.edit', $post)" wire:navigate class="justify-center">
                {{ __('Edit post') }}
            </flux:button>
            @if($post->isPublished())
                <flux:button variant="ghost" :href="route('posts.show', $post->slug)" target="_blank" rel="noopener noreferrer" class="justify-center">
                    {{ __('Live preview') }}
                </flux:button>
                <flux:button
                    variant="ghost"
                    wire:click="togglePublish"
                    wire:confirm="{{ __('Unpublish this post? It will no longer be visible on the public site.') }}"
                    class="justify-center"
                >
                    {{ __('Unpublish') }}
                </flux:button>
            @else
                <flux:button variant="ghost" wire:click="togglePublish" class="justify-center">
                    {{ __('Publish') }}
                </flux:button>
            @endif
            <flux:button
                variant="ghost"
                color="red"
                wire:click="delete"
                wire:confirm="{{ __('Delete this post? This cannot be undone.') }}"
                class="justify-center"
            >
                {{ __('Delete') }}
            </flux:button>
        </div>
    </div>

    {{-- Hero summary --}}
    <div class="overflow-hidden rounded-xl border border-zinc-200/90 bg-gradient-to-br from-white via-zinc-50/40 to-white shadow-sm dark:border-zinc-700/80 dark:from-zinc-900 dark:via-zinc-900/95 dark:to-zinc-950">
        <div class="border-b border-zinc-200/80 px-5 py-6 sm:px-8 sm:py-8 dark:border-zinc-700/60">
            <div class="flex flex-wrap items-center gap-2">
                @if($post->category)
                    <flux:badge color="zinc" size="sm" class="font-medium uppercase tracking-wide">{{ $post->category }}</flux:badge>
                @endif
                @if($post->isPublished())
                    <flux:badge color="green" size="sm">{{ __('Published') }}</flux:badge>
                @else
                    <flux:badge color="zinc" size="sm">{{ __('Draft') }}</flux:badge>
                @endif
            </div>

            <h1 class="mt-4 text-2xl font-semibold leading-tight tracking-tight text-zinc-900 sm:text-3xl dark:text-zinc-50">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
                <p class="mt-4 max-w-3xl text-base leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ $post->excerpt }}
                </p>
            @endif
        </div>

        <dl class="grid divide-y divide-zinc-200/80 dark:divide-zinc-700/60 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            <div class="bg-white/60 px-5 py-4 dark:bg-zinc-900/40 sm:px-6">
                <dt class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Public URL') }}</dt>
                <dd class="mt-1.5 break-all font-mono text-sm text-zinc-800 dark:text-zinc-200">
                    /intelligence/{{ $post->slug }}
                </dd>
            </div>
            <div class="bg-white/60 px-5 py-4 dark:bg-zinc-900/40 sm:px-6">
                <dt class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Updated') }}</dt>
                <dd class="mt-1.5 text-sm font-medium tabular-nums text-zinc-800 dark:text-zinc-200">
                    {{ $post->updated_at->timezone(config('app.timezone'))->format('M j, Y \a\t g:i A') }}
                </dd>
            </div>
            <div class="bg-white/60 px-5 py-4 dark:bg-zinc-900/40 sm:px-6">
                <dt class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Publication') }}</dt>
                <dd class="mt-1.5 text-sm font-medium text-zinc-800 dark:text-zinc-200">
                    @if($post->published_at)
                        {{ $post->published_at->timezone(config('app.timezone'))->format('M j, Y') }}
                    @else
                        <span class="text-zinc-500 dark:text-zinc-400">{{ __('Not published') }}</span>
                    @endif
                </dd>
                @if($post->user?->name)
                    <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                        {{ __('Created by') }} <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $post->user->name }}</span>
                    </p>
                @endif
            </div>
        </dl>
    </div>

    {{-- Body --}}
    <flux:card class="overflow-hidden shadow-sm">
        <div class="border-b border-zinc-200/80 bg-zinc-50/80 px-5 py-4 dark:border-zinc-700/60 dark:bg-zinc-800/40 sm:px-6">
            <flux:heading size="lg" class="text-zinc-900 dark:text-zinc-100">{{ __('Article content') }}</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Read-only preview of what is stored. Edit the post to change copy or layout.') }}
            </flux:text>
        </div>
        <div class="admin-post-body px-5 py-6 sm:px-8 sm:py-8">
            {!! $post->body ?? '' !!}
        </div>
    </flux:card>
</div>
