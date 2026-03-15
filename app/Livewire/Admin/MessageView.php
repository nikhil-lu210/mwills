<?php

namespace App\Livewire\Admin;

use App\Models\ConsultationMessage;
use Livewire\Component;

class MessageView extends Component
{
    public ConsultationMessage $message;

    public string $notes = '';

    public function mount(ConsultationMessage $message): void
    {
        $this->message = $message;
        $this->notes = $message->notes ?? '';

        if ($message->status === 'new') {
            $message->update(['status' => ConsultationMessage::STATUS_READ]);
            $this->message->refresh();
        }
    }

    public function updateStatus(string $status): void
    {
        $this->message->update(['status' => $status]);
        $this->message->refresh();
    }

    public function saveNotes(): void
    {
        $this->message->update(['notes' => $this->notes]);
        $this->dispatch('notes-saved');
    }

    public function render()
    {
        return view('livewire.admin.message-view')
            ->layout('layouts.app.sidebar', ['title' => __('Message')]);
    }
}
