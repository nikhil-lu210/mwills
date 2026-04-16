<?php

namespace App\Livewire\Admin;

use App\Models\ConsultationMessage;
use Livewire\Component;
use Livewire\WithPagination;

class LeadsList extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public string $periodFilter = '';

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPeriodFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ConsultationMessage::query()->latest();

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->periodFilter === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($this->periodFilter === 'week') {
            $query->where('created_at', '>=', now()->startOfWeek());
        } elseif ($this->periodFilter === 'month') {
            $query->where('created_at', '>=', now()->startOfMonth());
        }

        return view('livewire.admin.leads.index', [
            'leads' => $query->paginate(20),
        ])->layout('layouts.app.sidebar', [
            'title' => __('Leads'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => route('dashboard')],
                ['label' => __('Leads'), 'href' => null],
            ],
        ]);
    }
}
