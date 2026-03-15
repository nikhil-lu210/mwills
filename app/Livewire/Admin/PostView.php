<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Flux\Concerns\InteractsWithComponents;
use Livewire\Component;

class PostView extends Component
{
    use InteractsWithComponents;

    public Post $post;

    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    public function togglePublish(): void
    {
        if ($this->post->published_at) {
            $this->post->update(['published_at' => null]);
            $this->toast(__('Post unpublished. It is no longer visible on the public site.'), null, 5000, 'success');
            session()->flash('success', __('Post unpublished.'));
        } else {
            $this->post->update(['published_at' => now()]);
            $this->toast(__('Post published. It is now visible on the public site.'), null, 5000, 'success');
            session()->flash('success', __('Post published.'));
        }
        $this->post->refresh();
    }

    public function delete(): void
    {
        $this->post->delete();
        $this->toast(__('Post deleted.'), null, 5000, 'success');
        session()->flash('success', __('Post deleted.'));
        $this->redirect(route('admin.posts.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.blog.show')
            ->layout('layouts.app.sidebar', [
                'title' => $this->post->title,
                'breadcrumbs' => [
                    ['label' => __('Dashboard'), 'href' => route('dashboard')],
                    ['label' => __('Blogs'), 'href' => route('admin.posts.index')],
                    ['label' => __('All Posts'), 'href' => route('admin.posts.index')],
                    ['label' => $this->post->title, 'href' => null],
                ],
            ]);
    }
}
