<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Flux\Concerns\InteractsWithComponents;
use Livewire\Component;

class SiteSettings extends Component
{
    use InteractsWithComponents;

    public string $booking_embed_url = '';

    public function mount(): void
    {
        $this->booking_embed_url = (string) Setting::get('booking_embed_url', '');
    }

    public function save(): void
    {
        $this->validate([
            'booking_embed_url' => ['nullable', 'string', 'max:500', 'url'],
        ], [
            'booking_embed_url.url' => __('Please enter a valid URL.'),
        ]);

        Setting::set('booking_embed_url', $this->booking_embed_url ?: null);
        $this->toast(__('Settings saved.'), null, 5000, 'success');
        session()->flash('success', __('Settings saved.'));
    }

    public function render()
    {
        return view('livewire.admin.settings.index')
            ->layout('layouts.app.sidebar', [
                'title' => __('Site settings'),
                'breadcrumbs' => [
                    ['label' => __('Dashboard'), 'href' => route('dashboard')],
                    ['label' => __('Site settings'), 'href' => null],
                ],
            ]);
    }
}
