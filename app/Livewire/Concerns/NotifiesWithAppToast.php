<?php

namespace App\Livewire\Concerns;

trait NotifiesWithAppToast
{
    /**
     * Fixed top-right toast via window.__mwAppToast (see partials/app-toast.blade.php).
     * Flux's $this->toast() may not render without Flux Pro toast UI.
     */
    protected function notifyAppToast(string $message): void
    {
        $this->js('window.__mwAppToast && window.__mwAppToast('.json_encode($message).')');
    }
}
