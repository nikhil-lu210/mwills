<div class="space-y-6 w-full max-w-5xl">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
                <flux:link :href="route('admin.posts.index')" wire:navigate variant="ghost" class="-ms-2">
                    ← {{ __('Back to Posts') }}
                </flux:link>
            </flux:text>
            <div class="mt-2 flex flex-wrap items-center gap-2">
                @if($post->category)
                    <flux:badge color="zinc">{{ $post->category }}</flux:badge>
                @endif
                @if($post->isPublished())
                    <flux:badge color="green">{{ __('Published') }}</flux:badge>
                @else
                    <flux:badge color="zinc">{{ __('Draft') }}</flux:badge>
                @endif
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $post->updated_at->format('M j, Y') }}
                </flux:text>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <flux:button variant="primary" :href="route('admin.posts.edit', $post)" wire:navigate>
                {{ __('Edit') }}
            </flux:button>
            @if($post->isPublished())
                <flux:button variant="ghost" :href="route('posts.show', $post->slug)" target="_blank">
                    {{ __('Preview on site') }}
                </flux:button>
                <flux:button variant="ghost" wire:click="togglePublish" wire:confirm="{{ __('Unpublish this post? It will no longer be visible on the public site.') }}">
                    {{ __('Unpublish') }}
                </flux:button>
            @else
                <flux:button variant="ghost" wire:click="togglePublish">
                    {{ __('Publish') }}
                </flux:button>
            @endif
            <flux:button variant="ghost" color="red" wire:click="delete" wire:confirm="{{ __('Delete this post? This cannot be undone.') }}">
                {{ __('Delete') }}
            </flux:button>
        </div>
    </div>

    @if($post->excerpt)
        <flux:card class="border-l-4 border-l-zinc-300 dark:border-l-zinc-600">
            <flux:text class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('Excerpt') }}</flux:text>
            <p class="mt-2 text-zinc-800 dark:text-zinc-200 leading-relaxed">{{ $post->excerpt }}</p>
        </flux:card>
    @endif

    <flux:card>
        <flux:heading size="sm" class="mb-4 text-zinc-500 dark:text-zinc-400">{{ __('Content') }}</flux:heading>
        <div class="admin-post-body">
            {!! \App\Support\PostBody::enhanceLinksWithFavicons($post->body ?? '') !!}
        </div>
    </flux:card>
</div>
