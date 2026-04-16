<x-layouts.public title="Contact">
    <section class="bg-offwhite py-24 min-h-screen">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h1 class="font-display font-bold text-4xl md:text-5xl text-navy mb-4">Let's have a conversation.</h1>
                <p class="text-slate font-body text-lg max-w-2xl mx-auto">
                    Not a sales call. A conversation about your challenge. We'll tell you what we know and where we think the opportunity is.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-5xl mx-auto">
                <div class="bg-white p-8 md:p-10 shadow-sm border border-gray-100 rounded-sm">
                    <h3 class="font-bold text-xl text-navy mb-6">Send an Enquiry</h3>
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-semibold text-navy mb-1">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold bg-offwhite @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-semibold text-navy mb-1">Company Name *</label>
                            <input type="text" id="company" name="company" value="{{ old('company') }}" required
                                   class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold bg-offwhite @error('company') border-red-500 @enderror">
                            @error('company')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-navy mb-1">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold bg-offwhite @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="area" class="block text-sm font-semibold text-navy mb-1">Which area are you interested in?</label>
                            <select id="area" name="area" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold bg-offwhite text-navy">
                                <option value="">— Select an area (optional) —</option>
                                @foreach(\App\Models\ConsultationMessage::AREAS as $option)
                                    <option value="{{ $option }}" {{ old('area') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-semibold text-navy mb-1">Tell us what you're working on <span class="text-gray-400 font-normal">(Optional, 200 char max)</span></label>
                            <textarea id="message" name="message" rows="3" maxlength="200" class="w-full border border-gray-300 rounded-sm px-4 py-3 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold bg-offwhite resize-none">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" data-cta-track="contact_form_submit" class="w-full bg-navy text-white font-bold py-4 rounded-sm hover:bg-gold transition-colors duration-300 mt-2">
                            Start the Conversation
                        </button>
                    </form>
                </div>

                <div class="bg-navy p-8 md:p-10 flex flex-col justify-center items-center rounded-sm text-center border-l-4 border-gold">
                    <div class="w-16 h-16 bg-gold/20 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="font-display font-bold text-2xl text-white mb-4">Book Directly</h3>
                    <p class="text-gray-300 text-sm mb-8 leading-relaxed max-w-sm">
                        Skip the form and schedule a 30-minute Discovery Call straight into the practice calendar.
                    </p>
                    @php $bookingUrl = \App\Models\Setting::get('booking_embed_url') ?: config('services.booking_embed_url'); @endphp
                    @if($bookingUrl)
                        <a
                            href="{{ $bookingUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            data-cta-track="contact_booking_external"
                            class="inline-flex items-center justify-center gap-3 w-full sm:w-auto px-8 py-4 bg-gold text-navy font-bold text-base rounded-sm hover:bg-white transition-colors duration-300 shadow-lg hover:shadow-xl"
                        >
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ __('Book a Discovery Call') }}
                        </a>
                        <p class="text-slate text-xs mt-4 max-w-sm mx-auto">
                            {{ __('You’ll be taken to the booking page to pick a time that works for you.') }}
                        </p>
                    @else
                        <div class="w-full bg-white/5 border border-white/10 p-6 rounded-sm text-left">
                            <p class="text-slate text-xs uppercase tracking-widest font-bold mb-2">Calendly / TidyCal</p>
                            <p class="text-slate text-sm mb-4">Set the booking URL in <strong>Dashboard → Settings</strong> (or <code class="text-gold bg-white/10 px-1.5 py-0.5 rounded">BOOKING_EMBED_URL</code> in .env). The “Book a Discovery Call” button will appear here.</p>
                            <p class="text-slate text-xs">Example: <code class="text-gold/90 break-all">https://calendly.com/yourname/30min</code></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
