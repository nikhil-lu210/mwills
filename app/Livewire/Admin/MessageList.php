<?php

namespace App\Livewire\Admin;

use App\Models\ConsultationMessage;
use Livewire\Component;
use Livewire\WithPagination;

class MessageList extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ConsultationMessage::query()->latest();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin.message.index', [
            'messages' => $query->paginate(15),
        ])->layout('layouts.app.sidebar', [
            'title' => __('All Leads'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => route('dashboard')],
                ['label' => __('Leads'), 'href' => route('admin.leads.index')],
                ['label' => __('All Leads'), 'href' => null],
            ],
        ]);
    }
}
