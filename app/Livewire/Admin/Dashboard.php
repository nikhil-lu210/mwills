<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard.index')
            ->layout('layouts.app.sidebar', [
                'title' => __('Dashboard'),
                'breadcrumbs' => [
                    ['label' => __('Dashboard'), 'href' => null],
                ],
            ]);
    }
}
