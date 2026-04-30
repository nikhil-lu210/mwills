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

    public string $slug = '';

    public string $category = '';

    public string $excerpt = '';

    public string $body = '';

    public bool $publish = false;

    /** @internal Slug derived from the title before the latest title change (create flow only). */
    public string $slugBeforeTitleChange = '';

    public function mount(?Post $post = null): void
    {
        if ($post && $post->exists) {
            $this->postId = $post->id;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->category = $post->category ?? '';
            $this->excerpt = $post->excerpt ?? '';
            $this->body = $post->body ?? '';
            $this->publish = $post->published_at !== null;
        }
    }

    public function updatingTitle(): void
    {
        if (! $this->postId) {
            $this->slugBeforeTitleChange = Str::slug($this->title);
        }
    }

    public function updatedTitle(string $value): void
    {
        if ($this->postId) {
            return;
        }
        $newSlug = Str::slug($value);
        if ($this->slug === '' || $this->slug === $this->slugBeforeTitleChange) {
            $this->slug = $newSlug;
        }
    }

    public function save(): void
    {
        $this->ensureSlugFromTitle();

        try {
            $validated = $this->validate([
                'title' => ['required', 'string', 'max:255'],
                'slug' => [
                    'required',
                    'string',
                    'max:255',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        if (Str::slug((string) $value) === '') {
                            $fail(__('Could not build a URL slug from the title or slug field.'));
                        }
                    },
                ],
                'category' => ['nullable', 'string', 'max:100'],
                'excerpt' => ['nullable', 'string', 'max:500'],
                'body' => [
                    'required',
                    'string',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        $text = trim(html_entity_decode(strip_tags((string) $value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                        if (mb_strlen($text) < 10) {
                            $fail(__('Post body cannot be empty.'));
                        }
                    },
                ],
                'publish' => ['boolean'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->toast(__('Please fix the errors in the form.'), null, 6000, 'error');
            session()->flash('error', __('Please fix the errors in the form.'));
            throw $e;
        }

        $finalSlug = $this->uniqueSlug(Str::slug($validated['slug']), $this->postId);

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update([
                'title' => $validated['title'],
                'slug' => $finalSlug,
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
                'slug' => $finalSlug,
                'category' => $validated['category'] ?: null,
                'excerpt' => $validated['excerpt'] ?: null,
                'body' => $validated['body'] ?: null,
                'published_at' => $validated['publish'] ? now() : null,
                'user_id' => auth()->id(),
            ]);
            $this->toast(__('Post created successfully.'), null, 5000, 'success');
            session()->flash('success', __('Post created successfully.'));
            $this->redirect(route('admin.posts.show', $post), navigate: true);
        }
    }

    /**
     * If the slug is still empty (e.g. title was filled but Livewire never ran updatedTitle, or only native HTML validation ran),
     * derive it from the title so authors never need to type it.
     */
    private function ensureSlugFromTitle(): void
    {
        if (Str::slug($this->slug) !== '') {
            return;
        }

        $fromTitle = Str::slug($this->title);
        if ($fromTitle !== '') {
            $this->slug = $fromTitle;
        }
    }

    /**
     * Ensure a unique slug in the database by appending -1, -2, … when the base is taken.
     */
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
