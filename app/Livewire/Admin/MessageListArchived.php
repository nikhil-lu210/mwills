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
                ->where('status', ConsultationMessage::STATUS_CLOSED)
                ->latest()
                ->paginate(15),
            'pageSubheading' => __('Leads marked as closed.'),
        ])->layout('layouts.app.sidebar', [
            'title' => __('Closed Leads'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => route('dashboard')],
                ['label' => __('Leads'), 'href' => route('admin.leads.index')],
                ['label' => __('Closed'), 'href' => null],
            ],
        ]);
    }
}
