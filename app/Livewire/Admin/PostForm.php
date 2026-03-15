<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Component;

class PostForm extends Component
{
    public ?int $postId = null;

    public string $title = '';

    public string $category = '';

    public string $excerpt = '';

    public string $body = '';

    public ?int $read_time_minutes = null;

    public bool $publish = false;

    public function mount(?Post $post = null): void
    {
        if ($post && $post->exists) {
            $this->postId = $post->id;
            $this->title = $post->title;
            $this->category = $post->category ?? '';
            $this->excerpt = $post->excerpt ?? '';
            $this->body = $post->body ?? '';
            $this->read_time_minutes = $post->read_time_minutes;
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
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'read_time_minutes' => ['nullable', 'integer', 'min:1', 'max:120'],
            'publish' => ['boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update([
                'title' => $validated['title'],
                'slug' => $post->slug === $slug ? $post->slug : $this->uniqueSlug($slug, $post->id),
                'category' => $validated['category'] ?: null,
                'excerpt' => $validated['excerpt'] ?: null,
                'body' => $validated['body'] ?: null,
                'read_time_minutes' => $validated['read_time_minutes'],
                'published_at' => $validated['publish'] ? now() : null,
            ]);
            $this->redirect(route('admin.posts.edit', $post), navigate: true);
        } else {
            $post = Post::create([
                'title' => $validated['title'],
                'slug' => $this->uniqueSlug($slug),
                'category' => $validated['category'] ?: null,
                'excerpt' => $validated['excerpt'] ?: null,
                'body' => $validated['body'] ?: null,
                'read_time_minutes' => $validated['read_time_minutes'],
                'published_at' => $validated['publish'] ? now() : null,
                'user_id' => auth()->id(),
            ]);
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
        return view('livewire.admin.post-form')
            ->layout('layouts.app.sidebar', ['title' => $this->postId ? __('Edit Post') : __('New Post')]);
    }
}
