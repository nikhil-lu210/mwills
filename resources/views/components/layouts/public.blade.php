<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="McWills Consulting is a business development consultancy specialising in market intelligence, growth strategy, talent solutions, and executive communications.">
    <title>{{ $title ?? 'McWills Consulting' }} | Intelligence. Strategy. Growth.</title>

    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: var(--font-body);
            font-size: 16px;
            color: var(--color-navy);
        }

        @media (max-width: 768px) {
            body {
                font-size: 15px;
            }
        }

        h1,
        h2,
        .font-display {
            font-family: var(--font-display);
        }
    </style>
</head>

<body class="bg-offwhite antialiased text-navy">

    {{-- Sticky Navigation --}}
    <nav class="fixed w-full z-50 bg-white border-b border-gray-100 shadow-sm top-0 transition-all duration-300">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex flex-shrink-0 items-center gap-2 min-w-0">
                    @if(\File::exists(public_path('assets/images/logo.png')))
                        <img src="{{ asset('assets/images/logo.png') }}" alt="McWills Consulting"
                            class="h-8 w-auto max-h-10 max-w-[7rem] object-contain sm:h-9 sm:max-h-12 sm:max-w-[8rem]"
                            width="140" height="40" />
                    @endif
                    <span class="font-display font-bold text-xl tracking-tight text-navy sm:text-2xl truncate">
                        McWills <span class="text-gold">|</span> CONSULTING
                    </span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}"
                        class="text-sm font-semibold text-navy hover:text-gold transition-colors">Home</a>
                    <div class="relative group">
                        <button type="button"
                            class="text-sm font-semibold text-navy hover:text-gold transition-colors flex items-center">
                            What We Do
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div
                            class="absolute left-0 mt-2 w-72 rounded-sm shadow-lg bg-white ring-1 ring-black/5 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 border-t-2 border-gold">
                            <div class="py-2">
                                <a href="{{ route('services.strategy') }}"
                                    class="block px-4 py-3 text-sm text-navy hover:bg-offwhite hover:text-gold transition-colors">Strategy
                                    & Market Intelligence</a>
                                <a href="{{ route('services.bd') }}"
                                    class="block px-4 py-3 text-sm text-navy hover:bg-offwhite hover:text-gold transition-colors">Business
                                    Development & Growth</a>
                                <a href="{{ route('services.talent') }}"
                                    class="block px-4 py-3 text-sm text-navy hover:bg-offwhite hover:text-gold transition-colors">Talent
                                    & People Solutions</a>
                                <a href="{{ route('services.content') }}"
                                    class="block px-4 py-3 text-sm text-navy hover:bg-offwhite hover:text-gold transition-colors">Content
                                    & Communications</a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('intelligence') }}"
                        class="text-sm font-semibold text-navy hover:text-gold transition-colors">Intelligence</a>
                    <a href="{{ route('about') }}"
                        class="text-sm font-semibold text-navy hover:text-gold transition-colors">About</a>
                    <a href="{{ route('contact') }}"
                        class="ml-4 px-6 py-2.5 bg-gold text-navy font-semibold text-sm rounded-sm hover:bg-gold/90 hover:text-navy transition-all duration-300">
                        Book a Consultation
                    </a>
                </div>

                <details class="md:hidden group/menu">
                    <summary
                        class="list-none p-2 cursor-pointer text-navy hover:text-gold focus:outline-none [&::-webkit-details-marker]:hidden"
                        aria-label="Toggle menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </summary>
                    <div class="absolute left-0 right-0 top-20 bg-white border-t border-gray-100 shadow-lg">
                        <div class="px-4 pt-2 pb-3 space-y-1 sm:px-6 lg:px-8">
                            <a href="{{ route('home') }}"
                                class="block px-3 py-2 text-base font-semibold text-navy hover:text-gold">Home</a>
                            <div class="px-3 py-2 text-base font-semibold text-navy">
                                What We Do
                                <div class="pl-4 mt-2 space-y-2 border-l-2 border-gold">
                                    <a href="{{ route('services.strategy') }}"
                                        class="block text-sm text-navy hover:text-gold">Strategy & Market
                                        Intelligence</a>
                                    <a href="{{ route('services.bd') }}"
                                        class="block text-sm text-navy hover:text-gold">Business Development &
                                        Growth</a>
                                    <a href="{{ route('services.talent') }}"
                                        class="block text-sm text-navy hover:text-gold">Talent & People Solutions</a>
                                    <a href="{{ route('services.content') }}"
                                        class="block text-sm text-navy hover:text-gold">Content & Communications</a>
                                </div>
                            </div>
                            <a href="{{ route('intelligence') }}"
                                class="block px-3 py-2 text-base font-semibold text-navy hover:text-gold">Intelligence</a>
                            <a href="{{ route('about') }}"
                                class="block px-3 py-2 text-base font-semibold text-navy hover:text-gold">About</a>
                            <a href="{{ route('contact') }}"
                                class="block w-full text-left px-3 py-3 mt-4 bg-gold text-white font-semibold text-base rounded-sm">Book
                                a Consultation</a>
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </nav>

    <main class="pt-20 min-h-screen">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-navy py-12 border-t border-white/10 text-white">
        <div class="max-w-content mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-2">
                    <a href="https://mwills.test" class="flex flex-shrink-0 items-center gap-2 min-w-0 p-2">
                        <img src="https://mwills.test/assets/images/logo.png" alt="McWills Consulting"
                            class="h-8 w-auto max-h-10 max-w-[7rem] object-contain sm:h-9 sm:max-h-12 sm:max-w-[8rem]"
                            width="140" height="40">
                        <span class="font-display font-bold text-xl tracking-tight text-white sm:text-2xl truncate">
                            McWills <span class="text-gold">|</span> CONSULTING
                        </span>
                    </a>
                    <p class="text-slate text-sm max-w-sm">
                        Intelligence. Strategy. Growth. Where preparation meets execution.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-gold text-sm uppercase tracking-wider mb-4">Practice Areas</h4>
                    <ul class="space-y-2 text-sm text-slate">
                        <li><a href="{{ route('services.strategy') }}"
                                class="hover:text-white transition-colors">Strategy & Market Intelligence</a></li>
                        <li><a href="{{ route('services.bd') }}" class="hover:text-white transition-colors">Business
                                Development & Growth</a></li>
                        <li><a href="{{ route('services.talent') }}" class="hover:text-white transition-colors">Talent &
                                People Solutions</a></li>
                        <li><a href="{{ route('services.content') }}" class="hover:text-white transition-colors">Content
                                & Communications</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gold text-sm uppercase tracking-wider mb-4">Company</h4>
                    <ul class="space-y-2 text-sm text-slate">
                        <li><a href="{{ route('intelligence') }}"
                                class="hover:text-white transition-colors">Intelligence Desk</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About the
                                Practice</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div
                class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate">
                <p>&copy; {{ date('Y') }} McWills Consulting. All rights reserved.</p>
                <div class="space-x-4 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white">Privacy Policy</a>
                    <a href="#" class="hover:text-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>