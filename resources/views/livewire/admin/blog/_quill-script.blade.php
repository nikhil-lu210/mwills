<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" id="quill-snow-css">
<script type="module">
    (function() {
        const container = document.getElementById('quill-editor-container');
        const input = document.getElementById('post-body-input');
        if (!container || !input || container.querySelector('.ql-editor')) return;

        const uploadUrl = document.querySelector('[data-quill-upload-url]')?.dataset?.quillUploadUrl ?? '';

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || document.querySelector('input[name="_token"]')?.value
                || '';
        }

        async function uploadImageFile(file) {
            if (!uploadUrl) throw new Error('No upload URL');
            const formData = new FormData();
            formData.append('image', file);
            const token = getCsrfToken();
            if (token) formData.append('_token', token);
            const res = await fetch(uploadUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                throw new Error(err.message || 'Upload failed');
            }
            const data = await res.json();
            if (!data.url) throw new Error('No URL in response');
            return data.url;
        }

        async function dataUrlToFile(dataUrl) {
            const res = await fetch(dataUrl);
            const blob = await res.blob();
            const rawExt = (blob.type.split('/')[1] || 'png').split('+')[0];
            const ext = rawExt === 'jpeg' ? 'jpg' : rawExt;
            return new File([blob], 'inline-' + Date.now() + '.' + ext, { type: blob.type || 'image/png' });
        }

        function syncBodyToLivewire(quill) {
            input.value = quill.root.innerHTML;
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }

        let replacingDataImages = false;

        async function replaceDataImagesInEditor(quill) {
            if (!uploadUrl || replacingDataImages) return;
            const nodes = Array.from(quill.root.querySelectorAll('img[src^="data:"]'));
            if (!nodes.length) return;
            replacingDataImages = true;
            try {
                for (let i = 0; i < nodes.length; i++) {
                    const img = nodes[i];
                    const src = img.getAttribute('src');
                    if (!src || !src.startsWith('data:')) continue;
                    try {
                        const file = await dataUrlToFile(src);
                        const url = await uploadImageFile(file);
                        img.setAttribute('src', url);
                    } catch (e) {
                        console.error(e);
                    }
                }
                syncBodyToLivewire(quill);
            } finally {
                replacingDataImages = false;
            }
        }

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
                const q = window.quillInstance;
                if (!q) return;
                const file = fileInput.files?.[0];
                if (!file || !uploadUrl) {
                    const url = window.prompt('Image URL:');
                    if (url) q.insertEmbed(getInsertIndex(), 'image', url);
                    return;
                }
                const index = getInsertIndex();
                try {
                    const imageUrl = await uploadImageFile(file);
                    q.insertEmbed(index, 'image', imageUrl);
                    syncBodyToLivewire(q);
                } catch (e) {
                    const url = window.prompt('Upload failed. Paste image URL instead:');
                    if (url) q.insertEmbed(getInsertIndex(), 'image', url);
                }
            };
        }

        function videoHandler() {
            const q = window.quillInstance;
            if (!q) return;
            const url = window.prompt('YouTube or video embed URL:');
            if (!url) return;
            let embed = url.trim();
            const m = embed.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
            if (m) embed = 'https://www.youtube.com/embed/' + m[1];
            q.insertEmbed(getInsertIndex(), 'video', embed);
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
            if (uploadUrl) {
                replaceDataImagesInEditor(quill);
            }

            quill.root.addEventListener('paste', async function (e) {
                const items = e.clipboardData?.items;
                const hasHtml = items && Array.from(items).some(function (it) { return it.type === 'text/html'; });
                if (items && uploadUrl && !hasHtml) {
                    for (let i = 0; i < items.length; i++) {
                        if (items[i].type.indexOf('image') !== -1) {
                            const file = items[i].getAsFile();
                            if (file) {
                                e.preventDefault();
                                const index = quill.getSelection(true)?.index ?? Math.max(0, quill.getLength() - 1);
                                try {
                                    const imageUrl = await uploadImageFile(file);
                                    quill.insertEmbed(index, 'image', imageUrl);
                                    syncBodyToLivewire(quill);
                                } catch (err) {
                                    console.error(err);
                                }
                                return;
                            }
                        }
                    }
                }
                if (uploadUrl) {
                    setTimeout(function () { replaceDataImagesInEditor(quill); }, 250);
                }
            });

            quill.root.addEventListener('dragover', function (e) {
                if (e.dataTransfer && Array.from(e.dataTransfer.types || []).includes('Files')) {
                    e.preventDefault();
                }
            });

            quill.root.addEventListener('drop', async function (e) {
                const files = e.dataTransfer?.files;
                if (!files?.length || !uploadUrl) return;
                const images = Array.from(files).filter(function (f) { return f.type.startsWith('image/'); });
                if (!images.length) return;
                e.preventDefault();
                const range = quill.getSelection(true);
                let index = range ? range.index : Math.max(0, quill.getLength() - 1);
                let offset = 0;
                for (let j = 0; j < images.length; j++) {
                    try {
                        const imageUrl = await uploadImageFile(images[j]);
                        quill.insertEmbed(index + offset, 'image', imageUrl);
                        offset += 1;
                    } catch (err) {
                        console.error(err);
                    }
                }
                syncBodyToLivewire(quill);
            });

            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function syncQuillToInput(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    input.value = quill.root.innerHTML;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    form.removeEventListener('submit', syncQuillToInput, true);
                    setTimeout(function () { form.requestSubmit(); }, 150);
                }, true);
            }
        }
        init();
    })();
</script>
