<div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading>{{ __('Blog Posts') }}</flux:heading>
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Create and manage articles for the public Intelligence page.') }}
                </flux:text>
            </div>
            <flux:button :href="route('admin.posts.create')" variant="primary" wire:navigate class="w-full sm:w-auto justify-center">
                {{ __('New Post') }}
            </flux:button>
        </div>

        <flux:card class="overflow-hidden">
            <div class="min-w-0 overflow-x-auto">
                <flux:table container:class="max-h-[70vh] min-w-[640px]">
                <flux:table.columns>
                    <flux:table.column>{{ __('Title') }}</flux:table.column>
                    <flux:table.column>{{ __('Category') }}</flux:table.column>
                    <flux:table.column>{{ __('Status') }}</flux:table.column>
                    <flux:table.column>{{ __('Updated') }}</flux:table.column>
                    <flux:table.column class="text-end">{{ __('Actions') }}</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($posts as $post)
                        <flux:table.row>
                            <flux:table.cell>
                                <flux:link :href="route('admin.posts.edit', $post)" wire:navigate class="font-medium">
                                    {{ $post->title }}
                                </flux:link>
                            </flux:table.cell>
                            <flux:table.cell>{{ $post->category ?? '—' }}</flux:table.cell>
                            <flux:table.cell>
                                @if($post->isPublished())
                                    <flux:badge color="green">{{ __('Published') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc">{{ __('Draft') }}</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>{{ $post->updated_at->format('M j, Y') }}</flux:table.cell>
                            <flux:table.cell class="text-end">
                                @if($post->isPublished())
                                    <flux:button size="sm" variant="ghost" :href="route('posts.show', $post->slug)" target="_blank">
                                        {{ __('View') }}
                                    </flux:button>
                                @endif
                                <flux:button size="sm" variant="ghost" :href="route('admin.posts.edit', $post)" wire:navigate>
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button size="sm" variant="ghost" color="red" wire:click="delete({{ $post->id }})" wire:confirm="{{ __('Delete this post?') }}">
                                    {{ __('Delete') }}
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="py-12 text-center">
                                <flux:text class="block text-zinc-500 dark:text-zinc-400">{{ __('No posts yet.') }}</flux:text>
                                <flux:button :href="route('admin.posts.create')" variant="primary" wire:navigate class="mt-3">
                                    {{ __('Create your first post') }}
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
            </div>
            @if($posts->hasPages())
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </flux:card>
</div>
