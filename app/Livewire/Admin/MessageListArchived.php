<?php

namespace App\Livewire\Admin;

use App\Models\ConsultationMessage;
use Livewire\Component;
use Livewire\WithPagination;

class MessageListArchived extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.message.archive', [
            'messages' => ConsultationMessage::query()
                ->where('status', 'archived')
                ->latest()
                ->paginate(15),
            'pageSubheading' => __('Messages you have archived.'),
        ])->layout('layouts.app.sidebar', [
            'title' => __('Archived Messages'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => route('dashboard')],
                ['label' => __('Messages'), 'href' => route('admin.messages.index')],
                ['label' => __('Archived'), 'href' => null],
            ],
        ]);
    }
}
