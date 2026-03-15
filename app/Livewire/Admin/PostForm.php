<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Flux\Concerns\InteractsWithComponents;
use Illuminate\Support\Str;
use Livewire\Component;

class PostForm extends Component
{
    use InteractsWithComponents;

    public ?int $postId = null;

    public string $title = '';

    public string $category = '';

    public string $excerpt = '';

    public string $body = '';

    public bool $publish = false;

    public function mount(?Post $post = null): void
    {
        if ($post && $post->exists) {
            $this->postId = $post->id;
            $this->title = $post->title;
            $this->category = $post->category ?? '';
            $this->excerpt = $post->excerpt ?? '';
            $this->body = $post->body ?? '';
            $this->publish = $post->published_at !== null;
        }
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->postId) {
            $this->dispatch('slug-from-title', slug: Str::slug($value));
        }
    }

    public function save(): void
    {
        try {
            $validated = $this->validate([
                'title' => ['required', 'string', 'max:255'],
                'category' => ['nullable', 'string', 'max:100'],
                'excerpt' => ['nullable', 'string', 'max:500'],
                'body' => ['nullable', 'string'],
                'publish' => ['boolean'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->toast(__('Please fix the errors in the form.'), null, 6000, 'error');
            session()->flash('error', __('Please fix the errors in the form.'));
            throw $e;
        }

        $slug = Str::slug($validated['title']);
        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update([
                'title' => $validated['title'],
                'slug' => $post->slug === $slug ? $post->slug : $this->uniqueSlug($slug, $post->id),
                'category' => $validated['category'] ?: null,
                'excerpt' => $validated['excerpt'] ?: null,
                'body' => $validated['body'] ?: null,
                'published_at' => $validated['publish'] ? now() : null,
            ]);
            $this->toast(__('Post saved successfully.'), null, 5000, 'success');
            session()->flash('success', __('Post saved successfully.'));
            $this->redirect(route('admin.posts.edit', $post), navigate: true);
        } else {
            $post = Post::create([
                'title' => $validated['title'],
                'slug' => $this->uniqueSlug($slug),
                'category' => $validated['category'] ?: null,
                'excerpt' => $validated['excerpt'] ?: null,
                'body' => $validated['body'] ?: null,
                'published_at' => $validated['publish'] ? now() : null,
                'user_id' => auth()->id(),
            ]);
            $this->toast(__('Post created successfully.'), null, 5000, 'success');
            session()->flash('success', __('Post created successfully.'));
            $this->redirect(route('admin.posts.edit', $post), navigate: true);
        }
    }

    private function uniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $base = $slug;
        $count = 0;
        while (Post::where('slug', $slug)->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $base.'-'.(++$count);
        }

        return $slug;
    }

    public function render()
    {
        $title = $this->postId ? __('Edit Post') : __('Create Post');
        $breadcrumbs = [
            ['label' => __('Dashboard'), 'href' => route('dashboard')],
            ['label' => __('Blogs'), 'href' => route('admin.posts.index')],
            ['label' => __('All Posts'), 'href' => route('admin.posts.index')],
            ['label' => $title, 'href' => null],
        ];

        $view = $this->postId ? 'livewire.admin.blog.edit' : 'livewire.admin.blog.create';

        return view($view)
            ->layout('layouts.app.sidebar', ['title' => $title, 'breadcrumbs' => $breadcrumbs]);
    }
}
