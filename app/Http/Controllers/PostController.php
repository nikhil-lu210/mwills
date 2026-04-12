<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Home page with latest Intelligence Desk previews.
     */
    public function home(): View
    {
        $latestPosts = Post::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('home', ['latestPosts' => $latestPosts]);
    }

    /**
     * List all published blog posts (Intelligence / blog index).
     */
    public function index(): View
    {
        $posts = Post::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9);

        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show a single published post by slug (public).
     */
    public function show(string $slug): View
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        $suggestedPosts = $this->getSuggestedPosts($post, 3);



        return view('posts.show', [
            'post' => $post,
            'suggestedPosts' => $suggestedPosts,
        ]);
    }

    /**
     * Get suggested posts: same category first, then fill with latest. Excludes current post.
     */
    private function getSuggestedPosts(Post $post, int $limit): \Illuminate\Database\Eloquent\Collection
    {
        $query = Post::query()
            ->where('id', '!=', $post->id)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($post->category) {
            $sameCategory = (clone $query)->where('category', $post->category)
                ->latest('published_at')
                ->limit($limit)
                ->get();

            if ($sameCategory->count() >= $limit) {
                return $sameCategory;
            }

            $ids = $sameCategory->pluck('id')->push($post->id)->all();
            $remaining = $query->whereNotIn('id', $ids)
                ->latest('published_at')
                ->limit($limit - $sameCategory->count())
                ->get();

            return $sameCategory->merge($remaining)->take($limit);
        }

        return $query->latest('published_at')->limit($limit)->get();
    }
}
