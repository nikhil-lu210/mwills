<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\PostForm;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostFormTest extends TestCase
{
    use RefreshDatabase;

    private function longBody(): string
    {
        return '<p>'.str_repeat('Word ', 8).'</p>';
    }

    public function test_empty_editor_body_fails_validation(): void
    {
        $user = User::factory()->create();
        Livewire::actingAs($user)
            ->test(PostForm::class)
            ->set('title', 'A valid title')
            ->set('slug', 'a-valid-title')
            ->set('body', '<p><br></p>')
            ->call('save')
            ->assertHasErrors(['body']);
    }

    public function test_editing_title_does_not_change_slug(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'title' => 'Original',
            'slug' => 'original-slug',
            'body' => $this->longBody(),
            'published_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(PostForm::class, ['post' => $post])
            ->set('title', 'Updated headline')
            ->set('body', $this->longBody())
            ->call('save')
            ->assertHasNoErrors();

        $post->refresh();
        $this->assertSame('original-slug', $post->slug);
        $this->assertSame('Updated headline', $post->title);
    }

    public function test_save_derives_slug_from_title_when_slug_left_empty(): void
    {
        $user = User::factory()->create();
        Livewire::actingAs($user)
            ->test(PostForm::class)
            ->set('title', 'Auto slug from title only')
            ->set('slug', '')
            ->set('body', $this->longBody())
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('posts', [
            'title' => 'Auto slug from title only',
            'slug' => 'auto-slug-from-title-only',
        ]);
    }

    public function test_save_appends_numeric_suffix_when_slug_is_already_taken(): void
    {
        $user = User::factory()->create();
        Post::create([
            'title' => 'First',
            'slug' => 'shared-slug',
            'body' => $this->longBody(),
            'published_at' => now(),
        ]);

        Livewire::actingAs($user)
            ->test(PostForm::class)
            ->set('title', 'Second post')
            ->set('slug', 'shared-slug')
            ->set('body', $this->longBody())
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('posts', ['slug' => 'shared-slug']);
        $this->assertDatabaseHas('posts', ['slug' => 'shared-slug-1', 'title' => 'Second post']);
    }
}
