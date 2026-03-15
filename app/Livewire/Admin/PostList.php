<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Flux\Concerns\InteractsWithComponents;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PostList extends Component
{
    use InteractsWithComponents, WithPagination, WithoutUrlPagination;

    public function delete(int $id): void
    {
        Post::findOrFail($id)->delete();
        $this->toast(__('Post deleted.'), null, 5000, 'success');
        session()->flash('success', __('Post deleted.'));
    }

    public function render()
    {
        return view('livewire.admin.blog.index', [
            'posts' => Post::query()
                ->latest('updated_at')
                ->paginate(10),
        ])->layout('layouts.app.sidebar', [
            'title' => __('All Posts'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => route('dashboard')],
                ['label' => __('Blogs'), 'href' => route('admin.posts.index')],
                ['label' => __('All Posts'), 'href' => null],
            ],
        ]);
    }
}
