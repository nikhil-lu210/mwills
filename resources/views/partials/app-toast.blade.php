{{-- Vanilla JS: Livewire $this->js() calls window.__mwAppToast(message). No Alpine dependency. --}}
<div
    id="mw-app-toast-wrap"
    class="pointer-events-none fixed inset-x-0 top-4 z-[100] flex justify-center px-4 sm:justify-end sm:pr-8"
    style="display: none;"
    role="status"
    aria-live="polite"
>
    <div
        id="mw-app-toast-msg"
        class="pointer-events-auto max-w-sm rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 shadow-lg dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-100"
    ></div>
</div>

<script>
    window.__mwAppToast = function (message) {
        var wrap = document.getElementById('mw-app-toast-wrap');
        var msg = document.getElementById('mw-app-toast-msg');
        if (!wrap || !msg) {
            return;
        }
        msg.textContent = message || '';
        wrap.style.display = 'flex';
        clearTimeout(window.__mwAppToastTimer);
        window.__mwAppToastTimer = setTimeout(function () {
            wrap.style.display = 'none';
        }, 4500);
    };
</script>
