<div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading>{{ __('Blog Posts') }}</flux:heading>
            <flux:button :href="route('admin.posts.create')" variant="primary" wire:navigate>
                {{ __('New Post') }}
            </flux:button>
        </div>

        <flux:card>
            <flux:table>
                <flux:columns>
                    <flux:column>{{ __('Title') }}</flux:column>
                    <flux:column>{{ __('Category') }}</flux:column>
                    <flux:column>{{ __('Status') }}</flux:column>
                    <flux:column>{{ __('Updated') }}</flux:column>
                    <flux:column class="text-end">{{ __('Actions') }}</flux:column>
                </flux:columns>
                <flux:rows>
                    @forelse($posts as $post)
                        <flux:row>
                            <flux:cell>
                                <flux:link :href="route('admin.posts.edit', $post)" wire:navigate class="font-medium">
                                    {{ $post->title }}
                                </flux:link>
                            </flux:cell>
                            <flux:cell>{{ $post->category ?? '—' }}</flux:cell>
                            <flux:cell>
                                @if($post->isPublished())
                                    <flux:badge color="green">{{ __('Published') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc">{{ __('Draft') }}</flux:badge>
                                @endif
                            </flux:cell>
                            <flux:cell>{{ $post->updated_at->format('M j, Y') }}</flux:cell>
                            <flux:cell class="text-end">
                                <flux:button size="sm" variant="ghost" :href="route('admin.posts.edit', $post)" wire:navigate>
                                    {{ __('Edit') }}
                                </flux:button>
                                <flux:button size="sm" variant="ghost" color="red" wire:click="delete({{ $post->id }})" wire:confirm="{{ __('Delete this post?') }}">
                                    {{ __('Delete') }}
                                </flux:button>
                            </flux:cell>
                        </flux:row>
                    @empty
                        <flux:row>
                            <flux:cell colspan="5" class="text-center text-zinc-500 dark:text-zinc-400">
                                {{ __('No posts yet.') }}
                                <flux:link :href="route('admin.posts.create')" wire:navigate class="ms-1">{{ __('Create one') }}</flux:link>
                            </flux:cell>
                        </flux:row>
                    @endforelse
                </flux:rows>
            </flux:table>
            @if($posts->hasPages())
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </flux:card>
</div>
