/**
 * Blog post body: Quill 2 via Vite, Livewire-safe submit sync, image uploads with user-visible errors.
 */
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

function decodeInitialBodyB64(encoded) {
    if (!encoded) {
        return '';
    }
    try {
        const binary = Uint8Array.from(atob(encoded), (c) => c.charCodeAt(0));

        return new TextDecoder().decode(binary);
    } catch {
        return '';
    }
}

function getCsrfToken() {
    return (
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
        document.querySelector('input[name="_token"]')?.value ||
        ''
    );
}

function notifyUploadError(message) {
    if (window.__mwAppToast) {
        window.__mwAppToast(message, 'error');
    } else {
        window.alert(message);
    }
}

function getWireFromEl(el) {
    if (!window.Livewire || !el) {
        return null;
    }
    let cur = el;
    while (cur) {
        if (cur.hasAttribute?.('wire:id')) {
            return window.Livewire.find(cur.getAttribute('wire:id'));
        }
        cur = cur.parentElement;
    }

    return null;
}

/**
 * Ensure Livewire component state matches the textarea after Quill sync (avoids empty body on save).
 */
async function flushBodyToLivewire(input, html) {
    input.value = html;
    input.dispatchEvent(new Event('input', { bubbles: true }));

    const $wire = getWireFromEl(input);
    if ($wire && typeof $wire.$set === 'function') {
        $wire.$set('body', html, false);
    }

    await new Promise((resolve) => queueMicrotask(resolve));

    if (window.Alpine?.nextTick) {
        await window.Alpine.nextTick();
    }

    await new Promise((resolve) => {
        requestAnimationFrame(() => requestAnimationFrame(resolve));
    });

    if (!window.Livewire?.hook) {
        return;
    }

    try {
        await new Promise((resolve) => {
            let finished = false;
            const once = () => {
                if (finished) {
                    return;
                }
                finished = true;
                resolve();
            };

            let unsubscribe = null;
            try {
                unsubscribe = window.Livewire.hook('commit', (payload) => {
                    const succeed = payload?.succeed;
                    if (typeof succeed === 'function') {
                        succeed(() => once());
                    } else {
                        once();
                    }
                });
            } catch {
                once();

                return;
            }

            setTimeout(() => {
                try {
                    if (typeof unsubscribe === 'function') {
                        unsubscribe();
                    }
                } catch {
                    /* no-op */
                }
                once();
            }, 500);
        });
    } catch {
        /* hook shape may differ by Livewire version */
    }
}

async function uploadImageFile(uploadUrl, file) {
    if (!uploadUrl) {
        throw new Error('No upload URL');
    }
    const formData = new FormData();
    formData.append('image', file);
    const token = getCsrfToken();
    if (token) {
        formData.append('_token', token);
    }
    const res = await fetch(uploadUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': token,
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
        const msg =
            data.message ||
            (data.errors && Object.values(data.errors).flat().join(' ')) ||
            `Upload failed (HTTP ${res.status})`;
        throw new Error(msg);
    }
    if (!data.url) {
        throw new Error(data.message || 'No image URL in response');
    }

    return data.url;
}

function syncBodyToLivewire(quill, input) {
    input.value = quill.root.innerHTML;
    const $wire = getWireFromEl(input);
    if ($wire && typeof $wire.$set === 'function') {
        $wire.$set('body', input.value, false);
    }
    input.dispatchEvent(new Event('input', { bubbles: true }));
}

function escapeHtml(text) {
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function insertPastedUrlWithFavicon(quill, e, url, input) {
    try {
        const parsed = new URL(url);
        const favicon =
            'https://www.google.com/s2/favicons?domain=' +
            encodeURIComponent(parsed.hostname) +
            '&sz=32';
        e.preventDefault();
        const index = quill.getSelection(true)?.index ?? Math.max(0, quill.getLength() - 1);
        const hrefAttr = url.replace(/"/g, '&quot;');
        const linkHtml =
            '<p><a href="' +
            hrefAttr +
            '" target="_blank" rel="noopener noreferrer" class="post-link-with-favicon" data-mw-favicon="1"><img src="' +
            favicon +
            '" width="16" height="16" alt="" class="post-link-favicon" loading="lazy"/>' +
            escapeHtml(url) +
            '</a></p>';
        quill.clipboard.dangerouslyPasteHTML(index, linkHtml);
        syncBodyToLivewire(quill, input);

        return true;
    } catch {
        return false;
    }
}

async function dataUrlToFile(dataUrl) {
    const res = await fetch(dataUrl);
    const blob = await res.blob();
    const rawExt = (blob.type.split('/')[1] || 'png').split('+')[0];
    const ext = rawExt === 'jpeg' ? 'jpg' : rawExt;

    return new File([blob], 'inline-' + Date.now() + '.' + ext, { type: blob.type || 'image/png' });
}

function installQuillInstance(container, input, uploadUrl) {
    let replacingDataImages = false;

    async function replaceDataImagesInEditor(quill) {
        if (!uploadUrl || replacingDataImages) {
            return;
        }
        const nodes = Array.from(quill.root.querySelectorAll('img[src^="data:"]'));
        if (!nodes.length) {
            return;
        }
        replacingDataImages = true;
        try {
            for (let i = 0; i < nodes.length; i++) {
                const img = nodes[i];
                const src = img.getAttribute('src');
                if (!src || !src.startsWith('data:')) {
                    continue;
                }
                try {
                    const file = await dataUrlToFile(src);
                    const url = await uploadImageFile(uploadUrl, file);
                    img.setAttribute('src', url);
                } catch (e) {
                    console.error(e);
                    notifyUploadError(e.message || 'Could not upload pasted image.');
                }
            }
            syncBodyToLivewire(quill, input);
        } finally {
            replacingDataImages = false;
        }
    }

    function getInsertIndex(q) {
        const sel = q.getSelection(true);

        return sel ? sel.index : Math.max(0, q.getLength() - 1);
    }

    function imageHandler() {
        const fileInput = document.createElement('input');
        fileInput.setAttribute('type', 'file');
        fileInput.setAttribute('accept', 'image/*');
        fileInput.click();
        fileInput.onchange = async () => {
            const q = window.__mwQuillInstance;
            if (!q) {
                return;
            }
            const file = fileInput.files?.[0];
            if (!file || !uploadUrl) {
                const url = window.prompt('Image URL:');
                if (url) {
                    q.insertEmbed(getInsertIndex(q), 'image', url);
                }

                return;
            }
            const index = getInsertIndex(q);
            try {
                const imageUrl = await uploadImageFile(uploadUrl, file);
                q.insertEmbed(index, 'image', imageUrl);
                syncBodyToLivewire(q, input);
            } catch (e) {
                notifyUploadError(e.message || 'Image upload failed.');
                const url = window.prompt('Upload failed. Paste image URL instead:');
                if (url) {
                    q.insertEmbed(getInsertIndex(q), 'image', url);
                }
            }
        };
    }

    function videoHandler() {
        const q = window.__mwQuillInstance;
        if (!q) {
            return;
        }
        const url = window.prompt('YouTube or video embed URL:');
        if (!url) {
            return;
        }
        let embed = url.trim();
        const m = embed.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
        if (m) {
            embed = 'https://www.youtube.com/embed/' + m[1];
        }
        q.insertEmbed(getInsertIndex(q), 'video', embed);
    }

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
    window.__mwQuillInstance = quill;

    let initialHtml = (input.value || '').trim();
    const wrapper = container.closest('[data-initial-body]');
    if (wrapper?.dataset.initialBody) {
        initialHtml = decodeInitialBodyB64(wrapper.dataset.initialBody).trim() || initialHtml;
    }
    if (initialHtml) {
        quill.root.innerHTML = initialHtml;
    }
    if (uploadUrl) {
        replaceDataImagesInEditor(quill);
    }

    quill.root.addEventListener('paste', async function (e) {
        const htmlClip = e.clipboardData?.getData('text/html') || '';
        const plain = (e.clipboardData?.getData('text/plain') || '').trim();
        if (!htmlClip && /^https?:\/\/\S+$/i.test(plain)) {
            if (insertPastedUrlWithFavicon(quill, e, plain, input)) {
                return;
            }
        }

        const items = e.clipboardData?.items;
        const hasHtml = items && Array.from(items).some((it) => it.type === 'text/html');
        if (items && uploadUrl && !hasHtml) {
            for (let i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    const file = items[i].getAsFile();
                    if (file) {
                        e.preventDefault();
                        const index = quill.getSelection(true)?.index ?? Math.max(0, quill.getLength() - 1);
                        try {
                            const imageUrl = await uploadImageFile(uploadUrl, file);
                            quill.insertEmbed(index, 'image', imageUrl);
                            syncBodyToLivewire(quill, input);
                        } catch (err) {
                            console.error(err);
                            notifyUploadError(err.message || 'Could not upload pasted image.');
                        }

                        return;
                    }
                }
            }
        }
        if (uploadUrl) {
            setTimeout(() => replaceDataImagesInEditor(quill), 250);
        }
    });

    quill.root.addEventListener('dragover', (e) => {
        if (e.dataTransfer && Array.from(e.dataTransfer.types || []).includes('Files')) {
            e.preventDefault();
        }
    });

    quill.root.addEventListener('drop', async (e) => {
        const files = e.dataTransfer?.files;
        if (!files?.length || !uploadUrl) {
            return;
        }
        const images = Array.from(files).filter((f) => f.type.startsWith('image/'));
        if (!images.length) {
            return;
        }
        e.preventDefault();
        const range = quill.getSelection(true);
        let index = range ? range.index : Math.max(0, quill.getLength() - 1);
        let offset = 0;
        for (let j = 0; j < images.length; j++) {
            try {
                const imageUrl = await uploadImageFile(uploadUrl, images[j]);
                quill.insertEmbed(index + offset, 'image', imageUrl);
                offset += 1;
            } catch (err) {
                console.error(err);
                notifyUploadError(err.message || 'Could not upload dropped image.');
            }
        }
        syncBodyToLivewire(quill, input);
    });

    const form = input.closest('form');
    if (form) {
        form.addEventListener(
            'submit',
            async function onSyncSubmit(e) {
                if (form.dataset.quillSubmitting === '1') {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    return;
                }
                e.preventDefault();
                e.stopImmediatePropagation();
                form.dataset.quillSubmitting = '1';
                try {
                    const html = quill.root.innerHTML;
                    await flushBodyToLivewire(input, html);
                    form.removeEventListener('submit', onSyncSubmit, true);
                    form.requestSubmit();
                } finally {
                    delete form.dataset.quillSubmitting;
                }
            },
            true,
        );
    }

    return quill;
}

export function initMwPostFormQuill() {
    const container = document.getElementById('quill-editor-container');
    const input = document.getElementById('post-body-input');
    if (!container || !input) {
        return;
    }
    if (container.querySelector('.ql-editor')) {
        syncQuillContentFromWrapper();

        return;
    }

    const uploadUrl = document.querySelector('[data-quill-upload-url]')?.dataset?.quillUploadUrl ?? '';
    installQuillInstance(container, input, uploadUrl);
}

export function syncQuillContentFromWrapper() {
    const wrap = document.querySelector('[data-initial-body][data-quill-upload-url]');
    const q = window.__mwQuillInstance;
    if (!wrap || !q) {
        return;
    }
    const html = decodeInitialBodyB64(wrap.dataset.initialBody || '');
    const next = (html || '').trim();
    const cur = (q.root.innerHTML || '').trim();
    if (cur !== next) {
        q.root.innerHTML = html;
    }
}
