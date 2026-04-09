<x-layouts.public title="Home">
    {{-- Hero --}}
    <section class="bg-navy text-white py-20 lg:py-32 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 right-0 pointer-events-none flex justify-end items-center">
            <svg viewBox="0 0 500 500" class="h-[150%] w-auto fill-gold transform translate-x-1/4">
                <path d="M125.7,112.1c25.3-21.2,60.1-28.8,92.5-30.5c34.8-1.9,69.5,4.7,100.8,19.3c15.2,7.1,30.3,16,42.5,27.6 c18.1,17.2,27.5,41.2,35.4,65.3c11.5,35,17.6,71.7,21.8,108.1c4,34.5,4.8,69.5-0.7,103.8c-4.4,27.6-13.8,55-30.1,77.9 c-16.1,22.7-39.2,40.1-65.6,49.5c-30.3,10.8-63.4,12.3-95.2,6.5c-30.7-5.6-59.5-19.1-84.3-37.4c-22.9-16.9-43-38.3-58.4-62.7 c-16.2-25.6-26.7-54.7-31.5-84.5c-5.1-31.8-3.4-64.4,4.2-95.5c7.3-29.8,20.5-57.8,38.6-82.2C102.6,131.6,113.8,122.1,125.7,112.1z"/>
            </svg>
        </div>
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
                <span class="text-gold tracking-[0.2em] text-xs sm:text-sm font-semibold uppercase mb-5 block">
                    Intelligence. Strategy. Growth.
                </span>
                <h1 class="font-display font-bold text-4xl sm:text-5xl lg:text-6xl leading-tight sm:leading-[1.1] mb-6 text-center">
                    <span class="block">Your next market</span>
                    <span class="block">Your next hire.</span>
                    <span class="block">Your next story</span>
                    <span class="mt-5 block text-gold text-[1.9rem] sm:text-[2.1rem] lg:text-[2.3rem] leading-snug font-semibold">
                        We build the intelligence and infrastructure that gets you there.
                    </span>
                </h1>
                <p class="text-base sm:text-lg text-gray-200 mb-10 leading-relaxed font-body max-w-3xl mx-auto">
                    McWills Consulting is a business development consultancy specialising in market intelligence, growth strategy,
                    talent solutions, and executive communications with deep expertise in African market entry.
                </p>
                <div class="flex flex-col items-center gap-4">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('contact') }}" class="inline-flex items-center justify-center bg-gold text-navy font-semibold px-8 py-3.5 rounded-sm hover:bg-white transition-colors duration-300 text-sm sm:text-base">
                            Book a Consultation
                        </a>
                        <a
                            href="{{ asset('assets/docs/Africa_Market_Entry_Playbook_2025.pdf') }}"
                            download
                            class="inline-flex items-center justify-center border border-white/30 text-white font-semibold px-8 py-3.5 rounded-sm hover:bg-white/10 transition-colors duration-300 text-sm sm:text-base"
                        >
                            Download Africa Playbook
                        </a>
                    </div>
                    <span class="text-sm font-medium text-slate">or email <a href="mailto:contact@mcwillsconsulting.com" class="text-white hover:text-gold transition-colors font-semibold">contact@mcwillsconsulting.com</a></span>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white border-b border-gray-200">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-gray-100 py-10">
                <div class="px-6 text-center">
                    <div class="text-4xl sm:text-5xl font-display font-bold text-navy mb-3"><span class="counter" data-target="3000">0</span>+</div>
                    <div class="text-xs text-slate uppercase tracking-wider font-semibold">Avg post impressions<br>Market intelligence</div>
                </div>
                <div class="px-6 text-center">
                    <div class="text-4xl sm:text-5xl font-display font-bold text-navy mb-3"><span class="counter" data-target="54">0</span></div>
                    <div class="text-xs text-slate uppercase tracking-wider font-semibold">African markets.<br>We know the right ones.</div>
                </div>
                <div class="px-6 text-center">
                    <div class="text-4xl sm:text-5xl font-display font-bold text-navy mb-3"><span class="counter" data-target="4">0</span></div>
                    <div class="text-xs text-slate uppercase tracking-wider font-semibold">Practice disciplines.<br>One partner.</div>
                </div>
                <div class="px-6 text-center">
                    <div class="text-4xl sm:text-5xl font-display font-bold text-navy mb-3"><span class="counter" data-target="360">0</span>°</div>
                    <div class="text-xs text-slate uppercase tracking-wider font-semibold">Growth infrastructure<br>End-to-end execution</div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('.counter');
            const speed = 100;

            const animateCounters = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const updateCount = () => {
                            const target = +counter.getAttribute('data-target');
                            const count = +counter.innerText;
                            const inc = target / speed;

                            if (count < target) {
                                counter.innerText = Math.ceil(count + inc);
                                setTimeout(updateCount, 15);
                            } else {
                                counter.innerText = target;
                            }
                        };
                        updateCount();
                        observer.unobserve(counter);
                    }
                });
            };

            const observer = new IntersectionObserver(animateCounters, { threshold: 0.5 });
            counters.forEach(counter => observer.observe(counter));
        });
    </script>
    @endpush

    {{-- The Problem --}}
    <section class="bg-navy py-24 text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display font-bold text-3xl md:text-4xl text-white leading-tight">
                Most companies know exactly what they need, a new market, a better pipeline, the right hire, the words that convert. <br/><br/>
                <span class="text-gold">What they're missing is the expertise to execute it without the expensive lessons.</span>
            </h2>
        </div>
    </section>

    {{-- Four Service Disciplines --}}
    <section class="bg-offwhite py-24">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-gold tracking-widest text-xs font-bold uppercase mb-4 block">Our Practice Areas</span>
                <h2 class="font-display font-bold text-3xl md:text-4xl text-navy">Four disciplines. One growth infrastructure.</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-10 service-card">
                    <h3 class="font-bold text-xl text-gold mb-3 font-body">Strategy & Market Intelligence</h3>
                    <p class="text-navy font-semibold mb-6">Intelligence before a single euro is spent.</p>
                    <ul class="space-y-3 text-slate text-sm mb-8">
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Africa Market Entry strategy</li>
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Deep competitor analysis</li>
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Opportunity and white space mapping</li>
                    </ul>
                    <a href="{{ route('services.strategy') }}" class="text-navy font-semibold text-sm hover:text-gold transition-colors inline-flex items-center">
                        Learn More <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                <div class="bg-white p-10 service-card">
                    <h3 class="font-bold text-xl text-gold mb-3 font-body">Business Development & Growth</h3>
                    <p class="text-navy font-semibold mb-6">A pipeline is not a wish list. We build the infrastructure behind it.</p>
                    <ul class="space-y-3 text-slate text-sm mb-8">
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Qualified pipeline building</li>
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Commercial partnership structuring</li>
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Executive Assistant (BD-Focused) support</li>
                    </ul>
                    <a href="{{ route('services.bd') }}" class="text-navy font-semibold text-sm hover:text-gold transition-colors inline-flex items-center">
                        Learn More <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                <div class="bg-white p-10 service-card">
                    <h3 class="font-bold text-xl text-gold mb-3 font-body">Talent & People Solutions</h3>
                    <p class="text-navy font-semibold mb-6">The right people are a strategy decision, not an HR function.</p>
                    <ul class="space-y-3 text-slate text-sm mb-8">
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> 360 manpower sourcing & placement</li>
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Retained executive search mandates</li>
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Recruitment advisory & workforce planning</li>
                    </ul>
                    <a href="{{ route('services.talent') }}" class="text-navy font-semibold text-sm hover:text-gold transition-colors inline-flex items-center">
                        Learn More <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

                <div class="bg-white p-10 service-card">
                    <h3 class="font-bold text-xl text-gold mb-3 font-body">Content & Communications</h3>
                    <p class="text-navy font-semibold mb-6">What you say and how you say it is a competitive advantage.</p>
                    <ul class="space-y-3 text-slate text-sm mb-8">
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Executive ghostwriting & thought leadership</li>
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> High-stakes corporate copywriting</li>
                        <li class="flex items-start"><span class="text-gold mr-2">▪</span> Strategic LinkedIn growth programming</li>
                    </ul>
                    <a href="{{ route('services.content') }}" class="text-navy font-semibold text-sm hover:text-gold transition-colors inline-flex items-center">
                        Learn More <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Flagship Proof Point --}}
    <section class="bg-navy py-24 text-white">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl border-l-4 border-gold pl-6 md:pl-10">
                <h2 class="font-display font-bold text-3xl md:text-[40px] leading-tight mb-8">
                    P&G left. GSK left. Bayer left. Unilever cut manufacturing.<br/>
                    <span class="text-slate text-2xl md:text-3xl">Every headline called it a warning. We call it a vacancy.</span>
                </h2>
                <p class="text-lg text-gray-300 font-body mb-8 leading-relaxed">
                    Nigeria still has 220+ million people. The companies left because of model failure, not market failure. For the business that goes in prepared, that's not a risk. That's a runway.
                </p>
                <p class="text-gold font-semibold uppercase tracking-wider text-sm">
                    This is the intelligence depth we bring to every engagement, across every service line.
                </p>
            </div>
        </div>
    </section>

    {{-- Intelligence Preview --}}
    <section class="bg-white py-24 border-b border-gray-100">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12 border-b border-gray-200 pb-6">
                <div>
                    <span class="text-gold tracking-widest text-xs font-bold uppercase block mb-2">Insights</span>
                    <h2 class="font-display font-bold text-3xl text-navy">From the Intelligence Desk</h2>
                </div>
                <a href="{{ route('intelligence') }}" class="hidden md:inline-flex text-navy font-semibold text-sm hover:text-gold transition-colors items-center">
                    View All <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            @if($latestPosts->isEmpty())
                <div class="text-center py-8">
                    <p class="text-slate font-body">{{ __('New insights coming soon. Browse the archive when articles are published.') }}</p>
                    <a href="{{ route('intelligence') }}" class="inline-flex items-center gap-2 mt-4 text-navy font-semibold text-sm hover:text-gold transition-colors">
                        {{ __('The Intelligence Desk') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($latestPosts as $post)
                        <a href="{{ route('posts.show', $post->slug) }}" class="group block">
                            @if($post->category)
                                <span class="text-xs font-bold text-gold uppercase tracking-wider mb-3 block">{{ $post->category }}</span>
                            @endif
                            <h3 class="font-display font-bold text-xl text-navy mb-3 group-hover:text-gold transition-colors">{{ $post->title }}</h3>
                            <p class="text-slate text-sm mb-4 line-clamp-2">{{ $post->excerpt ?: \Str::limit(strip_tags($post->body ?? ''), 160) }}</p>
                            @if($post->read_time_minutes)
                                <span class="text-xs text-slate">{{ $post->read_time_minutes }} {{ __('min read') }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Homepage CTA --}}
    <section class="bg-navy py-24 text-center">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display font-bold text-4xl text-gold mb-6">Ready to move with intelligence?</h2>
            <p class="text-white text-lg font-body mb-10 leading-relaxed">
                Whether you're entering a new market, building a pipeline, making a key hire, or building the narrative that positions your business; let's have a conversation.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('contact') }}" class="inline-block bg-gold text-navy font-bold px-10 py-4 rounded-sm hover:bg-white transition-colors duration-300">
                    Book a Consultation
                </a>
                <span class="text-sm font-medium text-slate">or email <a href="mailto:contact@mcwillsconsulting.com" class="text-white hover:text-gold transition-colors font-semibold">contact@mcwillsconsulting.com</a></span>
            </div>
        </div>
    </section>
</x-layouts.public>
