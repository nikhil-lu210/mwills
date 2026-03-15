/**
 * App entry. Quill editor is loaded from CDN only on the post form page (see post-form.blade.php)
 * so it works even when the Vite dev server is not running (avoids 504 on quill.js).
 */
