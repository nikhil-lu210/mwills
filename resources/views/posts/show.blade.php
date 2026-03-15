<x-layouts.public :title="$post->title">
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
                @if($post->read_time_minutes)
                    <span>{{ $post->read_time_minutes }} {{ __('min read') }}</span>
                @endif
            </div>
        </header>

        @if($post->excerpt)
            <p class="text-lg text-navy/90 font-body mb-8 leading-relaxed border-l-4 border-gold pl-4">
                {{ $post->excerpt }}
            </p>
        @endif

        {{-- Body: prose for rich HTML from Quill --}}
        <div class="post-body">
            {!! $post->body !!}
        </div>

        <footer class="mt-12 pt-8 border-t border-gray-200">
            <a href="{{ route('intelligence') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gold hover:text-navy transition-colors">
                {{ __('All posts') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </footer>
    </article>
</x-layouts.public>
