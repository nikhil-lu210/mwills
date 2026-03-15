/**
 * Quill rich text editor for blog post body.
 * Loaded only on the post create/edit page. Syncs HTML with a hidden textarea for Livewire.
 */
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const UPLOAD_URL = document.querySelector('[data-quill-upload-url]')?.dataset?.quillUploadUrl ?? '';

function imageHandler() {
  const input = document.createElement('input');
  input.setAttribute('type', 'file');
  input.setAttribute('accept', 'image/*');
  input.click();

  input.onchange = async () => {
    const file = input.files?.[0];
    if (!file || !UPLOAD_URL) {
      const url = window.prompt('Image URL:');
      if (url) this.quill.insertEmbed(this.quill.getSelection(true).index, 'image', url);
      return;
    }

    const range = this.quill.getSelection(true);
    if (!range) return;

    const formData = new FormData();
    formData.append('image', file);

    try {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      const res = await fetch(UPLOAD_URL, {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': token ?? '',
          'Accept': 'application/json',
        },
      });
      if (!res.ok) throw new Error('Upload failed');
      const data = await res.json();
      this.quill.insertEmbed(range.index, 'image', data.url);
    } catch (e) {
      const url = window.prompt('Upload failed. Paste image URL instead:');
      if (url) this.quill.insertEmbed(range.index, 'image', url);
    }
  };
}

function videoHandler(value) {
  if (value) {
    let url = value;
    if (!/^https?:\/\//.test(url)) url = 'https://' + url;
    const m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
    if (m) url = 'https://www.youtube.com/embed/' + m[1];
    this.quill.insertEmbed(this.quill.getSelection(true).index, 'video', url);
  } else {
    const url = window.prompt('YouTube or video embed URL:');
    if (url) {
      let embed = url;
      const m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
      if (m) embed = 'https://www.youtube.com/embed/' + m[1];
      this.quill.insertEmbed(this.quill.getSelection(true).index, 'video', embed);
    }
  }
}

export function initQuillEditor() {
  const container = document.getElementById('quill-editor-container');
  const input = document.getElementById('post-body-input');
  if (!container || !input) return;

  const toolbarOptions = [
    [{ header: [1, 2, 3, false] }],
    ['bold', 'italic', 'underline', 'link'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['image', 'video'],
    ['blockquote', 'code-block'],
    ['clean'],
  ];

  const quill = new Quill(container, {
    theme: 'snow',
    placeholder: 'Write your post… Headings, lists, links, images and YouTube videos are supported.',
    modules: {
      toolbar: {
        container: toolbarOptions,
        handlers: {
          image: imageHandler,
          video: videoHandler,
        },
      },
    },
  });

  const initialHtml = (input.value || '').trim();
  if (initialHtml) {
    quill.root.innerHTML = initialHtml;
  }

  const form = input.closest('form');
  if (form) {
    form.addEventListener('submit', () => {
      input.value = quill.root.innerHTML;
    });
  }

  return quill;
}
