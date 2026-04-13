<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Flux\Concerns\InteractsWithComponents;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class UserList extends Component
{
    use InteractsWithComponents;

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);
        if ($user->isOwner()) {
            $this->toast(__('The primary admin account cannot be deleted.'), null, 6000, 'error');
            session()->flash('error', __('The primary admin account cannot be deleted.'));

            return;
        }
        if ($user->is(auth()->user())) {
            $this->toast(__('You cannot delete your own account from this screen.'), null, 6000, 'error');
            session()->flash('error', __('You cannot delete your own account from this screen.'));

            return;
        }

        $user->delete();
        $this->toast(__('User deleted.'), null, 5000, 'success');
        session()->flash('success', __('User deleted.'));
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);
        if ($user->isOwner()) {
            $this->toast(__('The primary admin account cannot be deactivated.'), null, 6000, 'error');

            return;
        }

        $user->update(['is_active' => ! $user->is_active]);
        $message = $user->is_active
            ? __('User activated.')
            : __('User deactivated.');
        $this->toast($message, null, 5000, 'success');
        session()->flash('success', $message);
    }

    public function render()
    {
        /** @var Collection<int, User> $users */
        $users = User::query()->orderBy('id')->get();

        return view('livewire.admin.users.index', [
            'users' => $users,
        ])->layout('layouts.app.sidebar', [
            'title' => __('All Users'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => route('dashboard')],
                ['label' => __('Users'), 'href' => route('admin.users.index')],
                ['label' => __('All Users'), 'href' => null],
            ],
        ]);
    }
}
