import { initMwPostFormQuill, syncQuillContentFromWrapper } from './quill-post-form.js';

let morphHookRegistered = false;

function registerMorphSync() {
    if (morphHookRegistered || !window.Livewire?.hook) {
        return;
    }
    morphHookRegistered = true;
    window.Livewire.hook('morph.updated', () => {
        syncQuillContentFromWrapper();
    });
}

function boot() {
    initMwPostFormQuill();
}

document.addEventListener('DOMContentLoaded', boot);
document.addEventListener('livewire:navigated', () => {
    syncQuillContentFromWrapper();
    boot();
});

document.addEventListener('livewire:init', registerMorphSync);
if (window.Livewire?.hook) {
    registerMorphSync();
}
