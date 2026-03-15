<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function delete(int $id): void
    {
        Post::findOrFail($id)->delete();
        $this->dispatch('post-deleted');
    }

    public function render()
    {
        return view('livewire.admin.post-list', [
            'posts' => Post::query()
                ->latest('updated_at')
                ->paginate(10),
        ])->layout('layouts.app.sidebar', ['title' => __('Blog Posts')]);
    }
}
