<div class="space-y-6 w-full max-w-5xl">
    <div>
        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
            <flux:link :href="route('admin.posts.index')" wire:navigate variant="ghost" class="-ms-2">
                ← {{ __('Back to Posts') }}
            </flux:link>
        </flux:text>
        <flux:text class="mt-1 block text-sm text-zinc-500 dark:text-zinc-400">
            {{ __('Draft, refine and publish thought leadership articles for McWills Intelligence.') }}
        </flux:text>
    </div>

    @include('livewire.admin.blog._form')
</div>
