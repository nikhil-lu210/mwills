<x-layouts.public title="About">
    <section class="bg-navy pt-32 pb-24 text-white text-center">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <h1
                class="font-display font-bold text-4xl md:text-5xl lg:text-[56px] leading-[1.15] mb-6 max-w-4xl mx-auto">
                Intelligence before assumption.
                <br>
                <span class="text-gold">Execution over advice.</span>
            </h1>
        </div>
    </section>

    <section class="bg-offwhite py-24">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <div class="lg:col-span-5">
                    <div
                        class="bg-navy aspect-[3/4] rounded-sm relative shadow-xl overflow-hidden flex items-center justify-center bg-gray-100">
                        @if(\File::exists(public_path('assets/images/founder_potrait.png')))
                            <img src="{{ asset('assets/images/founder_potrait.png') }}" alt="Founder Portrait"
                                class="w-full h-full object-cover top-object">
                        @else
                            <span class="text-slate text-sm font-semibold tracking-widest uppercase">Founder Portrait</span>
                        @endif
                    </div>
                </div>
                <div class="lg:col-span-7 flex flex-col justify-center">
                    <span class="text-gold tracking-widest text-xs font-bold uppercase mb-4 block">The Practice</span>
                    <h2 class="font-display font-bold text-3xl text-navy mb-6">Built to Connect Founders and Businesses
                        with Growth Opportunities, Insights and Support with Strategy Execution.</h2>
                    <div class="space-y-6 text-slate font-body leading-relaxed">
                        <p>
                            McWills Consulting is a business development consultancy serving growth-stage companies,
                            leadership teams, and founders who need expert-level execution, not just theoretical advice.
                        </p>
                        <p>
                            We operate across four core disciplines: Strategy, BD, Talent, and Content. While our
                            capabilities span global business development, our sharpest and most differentiated
                            expertise lies in African market entry. With headquarters in Nigeria, the Founder
                            established this practice to provide the on-ground Market intelligence, operation, and
                            execution strategy that most businesses lack when expanding to a new market.
                        </p>
                        <p>
                            Our philosophy is simple: <strong class="text-navy font-semibold">Intelligence before
                                assumption.</strong> We do not hand clients a report and wish them luck. Alongside our
                            network of specialists and partners across Nigeria, West Africa, North Africa, and South
                            Africa, we work the problem with you.
                        </p>
                    </div>
                    <div class="mt-10 border-l-4 border-gold pl-6">
                        <p class="font-display font-bold text-xl text-navy italic">
                            "If you want someone who have been in the problem before you; let's talk."
                        </p>
                    </div>
                    <div class="mt-10 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <a href="{{ route('contact') }}"
                            class="inline-block bg-navy text-white font-semibold px-8 py-3 rounded-sm hover:bg-gold transition-colors duration-300">
                            Start a Conversation
                        </a>
                        <span class="text-sm font-medium text-slate">or email <a
                                href="mailto:contact@mcwillsconsulting.com"
                                class="text-navy hover:text-gold transition-colors font-semibold">contact@mcwillsconsulting.com</a></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>