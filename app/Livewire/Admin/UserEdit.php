<?php

namespace App\Livewire\Admin;

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Flux\Concerns\InteractsWithComponents;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class UserEdit extends Component
{
    use InteractsWithComponents, ProfileValidationRules;

    public User $user;

    public string $name = '';

    public string $email = '';

    public ?string $password = '';

    public ?string $password_confirmation = '';

    public bool $is_active = true;

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = $user->is_active;
    }

    public function save(): void
    {
        $rules = [
            ...$this->profileRules($this->user->id),
        ];
        if (! $this->user->isOwner()) {
            $rules['is_active'] = ['boolean'];
        }
        if (filled($this->password)) {
            $rules['password'] = ['required', 'string', Password::default(), 'confirmed'];
        }

        $validated = $this->validate($rules);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (! $this->user->isOwner()) {
            $data['is_active'] = $validated['is_active'];
        }

        if (filled($this->password) && isset($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $this->user->update($data);

        $this->password = '';
        $this->password_confirmation = '';

        $this->toast(__('User updated.'), null, 5000, 'success');
        session()->flash('success', __('User updated.'));

        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.users.edit')->layout('layouts.app.sidebar', [
            'title' => __('Edit User'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => route('dashboard')],
                ['label' => __('Users'), 'href' => route('admin.users.index')],
                ['label' => $this->user->name, 'href' => null],
            ],
        ]);
    }
}
