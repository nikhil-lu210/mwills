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
                </div>
            </div>

            {{-- Body: full width (col-12), rich text editor --}}
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
                    {{ __('Use the toolbar for headings, bold, lists, links, images and YouTube videos.') }}
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

    {{-- Quill from CDN so the editor works without Vite dev server (avoids 504 on mwills.test:5173) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" id="quill-snow-css">
    <script type="module">
        (function() {
            const container = document.getElementById('quill-editor-container');
            const input = document.getElementById('post-body-input');
            if (!container || !input || container.querySelector('.ql-editor')) return;

            const uploadUrl = document.querySelector('[data-quill-upload-url]')?.dataset?.quillUploadUrl ?? '';

            function imageHandler() {
                const fileInput = document.createElement('input');
                fileInput.setAttribute('type', 'file');
                fileInput.setAttribute('accept', 'image/*');
                fileInput.click();
                fileInput.onchange = async () => {
                    const file = fileInput.files?.[0];
                    if (!file || !uploadUrl) {
                        const url = window.prompt('Image URL:');
                        if (url) quillInstance.insertEmbed(quillInstance.getSelection(true).index, 'image', url);
                        return;
                    }
                    const range = quillInstance.getSelection(true);
                    if (!range) return;
                    const formData = new FormData();
                    formData.append('image', file);
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const res = await fetch(uploadUrl, { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': token ?? '', 'Accept': 'application/json' } });
                        if (!res.ok) throw new Error('Upload failed');
                        const data = await res.json();
                        quillInstance.insertEmbed(range.index, 'image', data.url);
                    } catch (e) {
                        const url = window.prompt('Upload failed. Paste image URL instead:');
                        if (url) quillInstance.insertEmbed(range.index, 'image', url);
                    }
                };
            }

            function videoHandler(value) {
                const url = value || window.prompt('YouTube or video embed URL:');
                if (!url) return;
                let embed = url;
                const m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
                if (m) embed = 'https://www.youtube.com/embed/' + m[1];
                quillInstance.insertEmbed(quillInstance.getSelection(true).index, 'video', embed);
            }

            async function init() {
                const { default: Quill } = await import('https://esm.sh/quill@2.0.2');
                const quill = new Quill(container, {
                    theme: 'snow',
                    placeholder: 'Write your post… Headings, lists, links, images and YouTube videos are supported.',
                    modules: {
                        toolbar: {
                            container: [
                                [{ header: [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline', 'link'],
                                [{ list: 'ordered' }, { list: 'bullet' }],
                                ['image', 'video'],
                                ['blockquote', 'code-block'],
                                ['clean'],
                            ],
                            handlers: {
                                image: imageHandler,
                                video: videoHandler,
                            },
                        },
                    },
                });
                window.quillInstance = quill;
                let initialHtml = (input.value || '').trim();
                const wrapper = container.closest('[data-initial-body]');
                if (wrapper && wrapper.dataset.initialBody) {
                    try {
                        const b64 = wrapper.dataset.initialBody || '';
                        if (b64) initialHtml = decodeURIComponent(escape(atob(b64)));
                    } catch (e) {}
                }
                if (initialHtml) quill.root.innerHTML = initialHtml;
                const form = input.closest('form');
                if (form) {
                    form.addEventListener('submit', function syncQuillToInput(e) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        input.value = quill.root.innerHTML;
                        input.dispatchEvent(new Event('input', { bubbles: true }));
                        form.removeEventListener('submit', syncQuillToInput, true);
                        setTimeout(function() { form.requestSubmit(); }, 150);
                    }, true);
                }
            }
            init();
        })();
    </script>
</div>
