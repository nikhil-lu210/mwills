<x-layouts.public :title="__('The Intelligence Desk')">
    <section class="bg-navy text-white py-16 sm:py-20">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-gold tracking-widest text-xs font-bold uppercase block mb-3">{{ __('Intelligence') }}</span>
            <h1 class="font-display font-bold text-3xl sm:text-4xl lg:text-5xl leading-tight mb-4">
                {{ __('The Intelligence Desk') }}
            </h1>
            <p class="text-lg text-white/85 max-w-2xl font-body">
                {{ __('Insights, market intelligence and thought leadership from McWills Consulting.') }}
            </p>
        </div>
    </section>

    <section class="py-12 sm:py-16">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            @if($posts->isEmpty())
                <div class="text-center py-16">
                    <p class="text-slate font-body text-lg mb-6">{{ __('No articles yet. Check back soon.') }}</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gold font-semibold hover:text-navy transition-colors">
                        {{ __('Back to Home') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            @else
                <ul class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($posts as $post)
                        <li>
                            <article class="h-full flex flex-col rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden transition-shadow hover:shadow-md">
                                <div class="p-6 flex-1 flex flex-col">
                                    @if($post->category)
                                        <span class="text-xs font-semibold uppercase tracking-wider text-gold mb-2 block">{{ $post->category }}</span>
                                    @endif
                                    <h2 class="font-display font-bold text-xl text-navy mb-2 line-clamp-2">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-gold transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h2>
                                    <p class="text-sm text-slate mb-4 flex-1 line-clamp-3">{{ $post->excerpt ?: \Str::limit(strip_tags($post->body), 120) }}</p>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate pt-2 border-t border-gray-100">
                                        <time datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->format('M j, Y') }}</time>
                                    </div>
                                </div>
                                <div class="px-6 pb-6">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gold hover:text-navy transition-colors">
                                        {{ __('Read more') }}
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>

                @if($posts->hasPages())
                    <div class="mt-12 flex justify-center">
                        <nav class="flex items-center gap-2" aria-label="{{ __('Pagination') }}">
                            @if($posts->onFirstPage())
                                <span class="px-4 py-2 text-sm text-slate border border-gray-200 rounded-sm cursor-not-allowed">{{ __('Previous') }}</span>
                            @else
                                <a href="{{ $posts->previousPageUrl() }}" class="px-4 py-2 text-sm font-semibold text-navy border border-gray-200 rounded-sm hover:bg-offwhite hover:border-gold transition-colors">{{ __('Previous') }}</a>
                            @endif
                            <span class="px-4 py-2 text-sm text-slate">{{ __('Page') }} {{ $posts->currentPage() }} {{ __('of') }} {{ $posts->lastPage() }}</span>
                            @if($posts->hasMorePages())
                                <a href="{{ $posts->nextPageUrl() }}" class="px-4 py-2 text-sm font-semibold text-navy border border-gray-200 rounded-sm hover:bg-offwhite hover:border-gold transition-colors">{{ __('Next') }}</a>
                            @else
                                <span class="px-4 py-2 text-sm text-slate border border-gray-200 rounded-sm cursor-not-allowed">{{ __('Next') }}</span>
                            @endif
                        </nav>
                    </div>
                @endif
            @endif
        </div>
    </section>
</x-layouts.public>
