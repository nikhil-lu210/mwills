<x-layouts.public :title="$post->title">
    <x-slot:seo>
        <meta name="description" content="{{ $post->excerpt ?? \Str::limit(strip_tags($post->body), 150) }}">
        <meta name="keywords" content="{{ $post->category ?? 'Business, Strategy, Africa' }}">
        <link rel="canonical" href="{{ route('posts.show', $post->slug) }}">
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ route('posts.show', $post->slug) }}">
        <meta property="og:title" content="{{ $post->title }} | McWills Consulting">
        <meta property="og:description" content="{{ $post->excerpt ?? \Str::limit(strip_tags($post->body), 150) }}">
        <meta property="og:image" content="{{ asset('assets/images/logo.png') }}">
        <meta name="twitter:card" content="summary_large_image">
    </x-slot>

    <article class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        {{-- Back link --}}
        <a href="{{ route('intelligence') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate hover:text-gold transition-colors mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ __('Intelligence Desk') }}
        </a>

        {{-- Meta --}}
        <header class="mb-8">
            @if($post->category)
                <span class="inline-block text-xs font-semibold uppercase tracking-wider text-gold mb-3">{{ $post->category }}</span>
            @endif
            <h1 class="font-display font-bold text-3xl sm:text-4xl lg:text-[2.5rem] leading-tight text-navy mb-4">
                {{ $post->title }}
            </h1>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate">
                <time datetime="{{ $post->published_at?->toIso8601String() }}">
                    {{ $post->published_at?->format('F j, Y') }}
                </time>
            </div>
        </header>

        @if($post->excerpt)
            <p class="text-lg text-navy/90 font-body mb-8 leading-relaxed border-l-4 border-gold pl-4">
                {{ $post->excerpt }}
            </p>
        @endif

        {{-- Body (external links get a favicon for pasted URLs) --}}
        <div class="post-body">
            {!! \App\Support\PostBody::enhanceLinksWithFavicons($post->body ?? '') !!}
        </div>

        <footer class="mt-12 pt-8 border-t border-gray-200">
            <a href="{{ route('intelligence') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gold hover:text-navy transition-colors">
                {{ __('All posts') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </footer>

        {{-- Suggested Posts Section --}}
        @if(isset($suggestedPosts) && $suggestedPosts->count() > 0)
            <aside class="mt-16 pt-12 border-t border-gray-200">
                <h2 class="font-display font-bold text-2xl text-navy mb-8">{{ __('Suggested reads') }}</h2>
                <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($suggestedPosts as $suggested)
                        <li>
                            <a href="{{ route('posts.show', $suggested->slug) }}" class="group block h-full">
                                <article class="h-full flex flex-col rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md hover:border-gold/40 border-l-4 border-l-gold">
                                    @if($suggested->category)
                                        <span class="text-xs font-semibold uppercase tracking-wider text-gold mb-3 block">{{ $suggested->category }}</span>
                                    @endif
                                    <h3 class="font-display font-bold text-lg text-navy mb-2 line-clamp-2 group-hover:text-gold transition-colors">
                                        {{ $suggested->title }}
                                    </h3>
                                    <p class="text-sm text-slate line-clamp-2">{{ $suggested->excerpt ?: \Str::limit(strip_tags($suggested->body), 80) }}</p>
                                </article>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>
        @endif
    </article>
</x-layouts.public>