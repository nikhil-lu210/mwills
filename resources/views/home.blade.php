<x-layouts.public title="Home">
    {{-- Hero --}}
    <section class="bg-navy text-white py-20 lg:py-32 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 right-0 pointer-events-none flex justify-end items-center">
            <svg viewBox="0 0 500 500" class="h-[150%] w-auto fill-gold transform translate-x-1/4">
                <path d="M125.7,112.1c25.3-21.2,60.1-28.8,92.5-30.5c34.8-1.9,69.5,4.7,100.8,19.3c15.2,7.1,30.3,16,42.5,27.6 c18.1,17.2,27.5,41.2,35.4,65.3c11.5,35,17.6,71.7,21.8,108.1c4,34.5,4.8,69.5-0.7,103.8c-4.4,27.6-13.8,55-30.1,77.9 c-16.1,22.7-39.2,40.1-65.6,49.5c-30.3,10.8-63.4,12.3-95.2,6.5c-30.7-5.6-59.5-19.1-84.3-37.4c-22.9-16.9-43-38.3-58.4-62.7 c-16.2-25.6-26.7-54.7-31.5-84.5c-5.1-31.8-3.4-64.4,4.2-95.5c7.3-29.8,20.5-57.8,38.6-82.2C102.6,131.6,113.8,122.1,125.7,112.1z"/>
            </svg>
        </div>
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <span class="text-gold tracking-widest text-xs font-bold uppercase mb-6 block">Intelligence. Strategy. Growth.</span>
                <h1 class="font-display font-bold text-4xl sm:text-5xl lg:text-[56px] leading-[1.15] mb-8">
                    Your next market. Your next hire. Your next client. Your next story.<br/>
                    <span class="text-gold">We build the intelligence and infrastructure that gets you there.</span>
                </h1>
                <p class="text-lg text-gray-300 mb-10 leading-relaxed font-body max-w-2xl">
                    McWills Consulting is a business development consultancy specialising in market intelligence, growth strategy, talent solutions, and executive communications — with deep expertise in African market entry.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('contact') }}" class="inline-block text-center bg-gold text-navy font-semibold px-8 py-4 rounded-sm hover:bg-white transition-colors duration-300">
                        Book a Consultation
                    </a>
                    <a href="#" class="inline-block text-center border border-white/30 text-white font-semibold px-8 py-4 rounded-sm hover:bg-white/10 transition-colors duration-300">
                        Download Africa Playbook
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Credibility Strip --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-gray-100 py-10">
                <div class="px-6 text-center">
                    <div class="text-3xl font-display font-bold text-navy mb-2">3,000+</div>
                    <div class="text-xs text-slate uppercase tracking-wide">Avg post impressions<br>Market intelligence</div>
                </div>
                <div class="px-6 text-center">
                    <div class="text-3xl font-display font-bold text-navy mb-2">54</div>
                    <div class="text-xs text-slate uppercase tracking-wide">African markets.<br>We know the right ones.</div>
                </div>
                <div class="px-6 text-center">
                    <div class="text-3xl font-display font-bold text-navy mb-2">4</div>
                    <div class="text-xs text-slate uppercase tracking-wide">Practice disciplines.<br>One partner.</div>
                </div>
                <div class="px-6 text-center">
                    <div class="text-3xl font-display font-bold text-navy mb-2">360°</div>
                    <div class="text-xs text-slate uppercase tracking-wide">Growth infrastructure<br>End-to-end execution</div>
                </div>
            </div>
        </div>
    </section>

    {{-- The Problem --}}
    <section class="bg-navy py-24 text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display font-bold text-3xl md:text-4xl text-white leading-tight">
                Most companies know exactly what they need — a new market, a better pipeline, the right hire, the words that convert. <br/><br/>
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
                    Nigeria still has 220+ million people. The companies left because of model failure, not market failure. For the business that goes in prepared — that's not a risk. That's a runway.
                </p>
                <p class="text-gold font-semibold uppercase tracking-wider text-sm">
                    This is the intelligence depth we bring to every engagement — across every service line.
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="{{ route('intelligence') }}" class="group block">
                    <span class="text-xs font-bold text-gold uppercase tracking-wider mb-3 block">The Vacancy</span>
                    <h3 class="font-display font-bold text-xl text-navy mb-3 group-hover:text-gold transition-colors">The Model, Not the Market, Determines Outcomes in West Africa</h3>
                    <p class="text-slate text-sm mb-4 line-clamp-2">Why European standard operating procedures consistently fail in markets that require adaptive commercial architecture.</p>
                    <span class="text-xs text-slate">5 min read</span>
                </a>
                <a href="{{ route('intelligence') }}" class="group block">
                    <span class="text-xs font-bold text-gold uppercase tracking-wider mb-3 block">BD & Growth</span>
                    <h3 class="font-display font-bold text-xl text-navy mb-3 group-hover:text-gold transition-colors">Enter Narrow. Scale with Evidence.</h3>
                    <p class="text-slate text-sm mb-4 line-clamp-2">The fatal flaw in multi-country simultaneous expansion and why a single-node entry strategy preserves capital.</p>
                    <span class="text-xs text-slate">4 min read</span>
                </a>
                <a href="{{ route('intelligence') }}" class="group block">
                    <span class="text-xs font-bold text-gold uppercase tracking-wider mb-3 block">Talent Intelligence</span>
                    <h3 class="font-display font-bold text-xl text-navy mb-3 group-hover:text-gold transition-colors">Why In-Market Leadership Hires Fail</h3>
                    <p class="text-slate text-sm mb-4 line-clamp-2">The discrepancy between expat expectations and local market realities, and how to source leaders who bridge the gap.</p>
                    <span class="text-xs text-slate">6 min read</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Homepage CTA --}}
    <section class="bg-navy py-24 text-center">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-display font-bold text-4xl text-gold mb-6">Ready to move with intelligence?</h2>
            <p class="text-white text-lg font-body mb-10 leading-relaxed">
                Whether you're entering a new market, building a pipeline, making a key hire, or building the narrative that positions your business — let's have a conversation.
            </p>
            <a href="{{ route('contact') }}" class="inline-block bg-gold text-navy font-bold px-10 py-4 rounded-sm hover:bg-white transition-colors duration-300">
                Book a Consultation
            </a>
        </div>
    </section>
</x-layouts.public>
