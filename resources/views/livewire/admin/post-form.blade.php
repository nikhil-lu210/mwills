<div class="space-y-6 w-full max-w-5xl">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
                <flux:link :href="route('admin.posts.index')" wire:navigate variant="ghost" class="-ms-2">
                    ← {{ __('Back to Posts') }}
                </flux:link>
            </flux:text>
            <flux:heading class="mt-1">
                {{ $postId ? __('Edit post') : __('Create post') }}
            </flux:heading>
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Draft, refine and publish thought leadership articles for McWills Intelligence.') }}
            </flux:text>
        </div>
    </div>

    <flux:card>
        <form wire:submit="save" class="space-y-6">
            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-6">
                    <flux:field>
                        <flux:label>{{ __('Title') }}</flux:label>
                        <flux:input wire:model="title" placeholder="{{ __('Post title') }}" required />
                        <flux:error name="title" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Category') }}</flux:label>
                        <flux:select wire:model="category" placeholder="{{ __('Select category') }}">
                            @foreach(\App\Models\Post::categoryOptions() as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="category" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Read time (minutes)') }}</flux:label>
                        <flux:input type="number" wire:model="read_time_minutes" min="1" max="120" placeholder="5" />
                        <flux:error name="read_time_minutes" />
                    </flux:field>

                    <flux:field>
                        <flux:checkbox wire:model="publish" :label="__('Publish now')" />
                    </flux:field>
                </div>

                <div class="space-y-6">
                    <flux:field>
                        <flux:label>{{ __('Excerpt') }}</flux:label>
                        <flux:textarea wire:model="excerpt" placeholder="{{ __('Short summary that appears in lists') }}" rows="3" />
                        <flux:error name="excerpt" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Body') }}</flux:label>
                        <flux:textarea
                            wire:model="body"
                            placeholder="{{ __('Full article content. You can paste formatted text or simple HTML.') }}"
                            rows="10"
                        />
                        <flux:error name="body" />
                    </flux:field>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-zinc-200 dark:border-zinc-800">
                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('Changes are saved when you click Save. Publishing will make the post visible on the public site.') }}
                </flux:text>

                <div class="flex flex-col gap-2 sm:flex-row sm:gap-3 sm:justify-end">
                    <flux:button variant="ghost" :href="route('admin.posts.index')" wire:navigate class="justify-center">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary" class="justify-center">
                        {{ $postId ? __('Save changes') : __('Create post') }}
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:card>
</div>
