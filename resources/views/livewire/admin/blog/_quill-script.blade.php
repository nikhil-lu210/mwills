<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" id="quill-snow-css">
<script type="module">
    (function() {
        const container = document.getElementById('quill-editor-container');
        const input = document.getElementById('post-body-input');
        if (!container || !input || container.querySelector('.ql-editor')) return;

        const uploadUrl = document.querySelector('[data-quill-upload-url]')?.dataset?.quillUploadUrl ?? '';

        function getInsertIndex() {
            const q = window.quillInstance;
            if (!q) return 0;
            const sel = q.getSelection(true);
            return sel ? sel.index : Math.max(0, q.getLength() - 1);
        }

        function imageHandler() {
            const fileInput = document.createElement('input');
            fileInput.setAttribute('type', 'file');
            fileInput.setAttribute('accept', 'image/*');
            fileInput.click();
            fileInput.onchange = async () => {
                const file = fileInput.files?.[0];
                if (!file || !uploadUrl) {
                    const url = window.prompt('Image URL:');
                    if (url) quillInstance.insertEmbed(getInsertIndex(), 'image', url);
                    return;
                }
                const index = getInsertIndex();
                const formData = new FormData();
                formData.append('image', file);
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;
                if (token) formData.append('_token', token);
                try {
                    const res = await fetch(uploadUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': token || '',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    if (!res.ok) {
                        const err = await res.json().catch(() => ({}));
                        throw new Error(err.message || 'Upload failed');
                    }
                    const data = await res.json();
                    if (data.url) quillInstance.insertEmbed(index, 'image', data.url);
                } catch (e) {
                    const url = window.prompt('Upload failed. Paste image URL instead:');
                    if (url) quillInstance.insertEmbed(getInsertIndex(), 'image', url);
                }
            };
        }

        function videoHandler() {
            const url = window.prompt('YouTube or video embed URL:');
            if (!url) return;
            let embed = url.trim();
            const m = embed.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
            if (m) embed = 'https://www.youtube.com/embed/' + m[1];
            quillInstance.insertEmbed(getInsertIndex(), 'video', embed);
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
                            [{ color: [] }, { background: [] }],
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
