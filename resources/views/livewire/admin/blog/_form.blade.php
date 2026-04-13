<flux:card>
    <form wire:submit="save" class="space-y-6">
        <div class="grid gap-6 grid-cols-12">
            <div class="col-span-12 md:col-span-8">
                <flux:field>
                    <flux:label>{{ __('Title') }}</flux:label>
                    <flux:input wire:model="title" placeholder="{{ __('Post title') }}" required />
                    <flux:error name="title" />
                </flux:field>
            </div>
            <div class="col-span-12 md:col-span-4">
                <flux:field>
                    <flux:label>{{ __('Category') }}</flux:label>
                    <flux:select wire:model="category" placeholder="{{ __('Select category') }}">
                        @foreach(\App\Models\Post::categoryOptions() as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="category" />
                </flux:field>
            </div>

            <div class="col-span-12">
                <flux:field>
                    <flux:label>{{ __('Excerpt') }}</flux:label>
                    <flux:textarea wire:model="excerpt" placeholder="{{ __('Short summary that appears in lists') }}" rows="3" />
                    <flux:error name="excerpt" />
                </flux:field>
            </div>

            <div class="col-span-12">
                <flux:field>
                    <flux:checkbox wire:model="publish" :label="__('Publish now')" />
                </flux:field>
            </div>
        </div>

        {{-- Body: full width, rich text editor --}}
        <flux:field class="w-full">
            <flux:label>{{ __('Body') }}</flux:label>
            <div
                data-quill-upload-url="{{ route('admin.upload.image') }}"
                data-initial-body="{{ base64_encode($body) }}"
                class="post-body-editor-wrapper rounded-xl border border-zinc-200 bg-white shadow-sm transition focus-within:border-zinc-400 focus-within:ring-2 focus-within:ring-zinc-200 dark:border-zinc-700 dark:bg-zinc-900 dark:focus-within:border-zinc-500 dark:focus-within:ring-zinc-800"
            >
                <textarea
                    id="post-body-input"
                    name="body"
                    wire:model="body"
                    class="sr-only"
                    aria-hidden="true"
                ></textarea>
                <div id="quill-editor-container" wire:ignore class="min-h-[320px] [&_.ql-editor]:min-h-[280px] [&_.ql-editor]:text-base"></div>
            </div>
            <flux:error name="body" />
            <flux:text class="mt-2 block text-xs text-zinc-500 dark:text-zinc-400">
                {{ __('Use the toolbar for headings, bold, lists, links, text color, images and YouTube videos. Pasting a single URL adds a site favicon next to the link. Pasted or dropped images are uploaded as files so the post saves reliably.') }}
            </flux:text>
        </flux:field>

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

@include('livewire.admin.blog._quill-script')
