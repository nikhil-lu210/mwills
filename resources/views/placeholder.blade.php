<x-layouts.public :title="$title ?? 'Page'">
    <section class="bg-offwhite py-24 min-h-[60vh] flex items-center justify-center">
        <div class="max-w-content mx-auto px-4 text-center">
            <h1 class="font-display font-bold text-3xl text-navy mb-4">{{ $title ?? 'Coming soon' }}</h1>
            <p class="text-slate font-body mb-8">{{ $message ?? 'This page is under construction and will be available soon.' }}</p>
            <a href="{{ route('home') }}" class="inline-block bg-navy text-white font-semibold px-6 py-3 rounded-sm hover:bg-gold transition-colors">Back to Home</a>
        </div>
    </section>
</x-layouts.public>
