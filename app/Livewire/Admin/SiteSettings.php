<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Flux\Concerns\InteractsWithComponents;
use Livewire\Component;

class SiteSettings extends Component
{
    use InteractsWithComponents;

    public string $booking_embed_url = '';

    public string $mail_mailer = '';
    public string $mail_host = '';
    public string $mail_port = '';
    public string $mail_username = '';
    public string $mail_password = '';
    public string $mail_from_address = '';
    public string $mail_from_name = '';
    public string $enquiry_recipient_email = '';

    public function mount(): void
    {
        $this->booking_embed_url = (string) Setting::get('booking_embed_url', '');

        $this->mail_mailer = (string) Setting::get('mail_mailer', config('mail.default'));
        $this->mail_host = (string) Setting::get('mail_host', config('mail.mailers.smtp.host'));
        $this->mail_port = (string) Setting::get('mail_port', (string) config('mail.mailers.smtp.port'));
        $this->mail_username = (string) Setting::get('mail_username', config('mail.mailers.smtp.username'));
        $this->mail_password = (string) Setting::get('mail_password', config('mail.mailers.smtp.password'));
        $this->mail_from_address = (string) Setting::get('mail_from_address', config('mail.from.address'));
        $this->mail_from_name = (string) Setting::get('mail_from_name', config('mail.from.name'));
        $this->enquiry_recipient_email = (string) Setting::get('enquiry_recipient_email', config('mail.from.address'));
    }

    public function save(): void
    {
        $this->validate([
            'booking_embed_url' => ['nullable', 'string', 'max:500', 'url'],
            'mail_mailer' => ['required', 'string', 'max:50'],
            'mail_host' => ['required', 'string', 'max:255'],
            'mail_port' => ['required', 'integer'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_from_address' => ['required', 'email', 'max:255'],
            'mail_from_name' => ['required', 'string', 'max:255'],
            'enquiry_recipient_email' => ['required', 'email', 'max:255'],
        ], [
            'booking_embed_url.url' => __('Please enter a valid URL.'),
        ]);

        Setting::set('booking_embed_url', $this->booking_embed_url ?: null);
        Setting::set('mail_mailer', $this->mail_mailer ?: null);
        Setting::set('mail_host', $this->mail_host ?: null);
        Setting::set('mail_port', $this->mail_port ?: null);
        Setting::set('mail_username', $this->mail_username ?: null);
        Setting::set('mail_password', $this->mail_password ?: null);
        Setting::set('mail_from_address', $this->mail_from_address ?: null);
        Setting::set('mail_from_name', $this->mail_from_name ?: null);
        Setting::set('enquiry_recipient_email', $this->enquiry_recipient_email ?: null);

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
