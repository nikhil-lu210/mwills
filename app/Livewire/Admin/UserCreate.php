<?php

namespace App\Livewire\Admin;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Flux\Concerns\InteractsWithComponents;
use Livewire\Component;

class UserCreate extends Component
{
    use InteractsWithComponents, PasswordValidationRules, ProfileValidationRules;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $is_active = true;

    public function save(): void
    {
        $validated = $this->validate([
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'is_active' => ['boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_active' => $validated['is_active'],
            'email_verified_at' => now(),
        ]);

        $this->toast(__('User created.'), null, 5000, 'success');
        session()->flash('success', __('User created.'));

        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.users.create')->layout('layouts.app.sidebar', [
            'title' => __('Create New User'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => route('dashboard')],
                ['label' => __('Users'), 'href' => route('admin.users.index')],
                ['label' => __('Create New User'), 'href' => null],
            ],
        ]);
    }
}
