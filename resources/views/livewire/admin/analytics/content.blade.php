@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
@endpush

<div
    class="mx-auto w-full min-w-0 max-w-full overflow-x-hidden"
    wire:key="content-{{ $periodDays }}"
>
    @if(!$gaConfigured)
        <flux:callout variant="warning" icon="exclamation-triangle" class="mb-6 rounded-lg break-words text-sm leading-relaxed">
            {{ __('Configure Google Analytics under Site settings → Analytics to see content metrics.') }}
        </flux:callout>
    @endif

    {{-- Header --}}
    <div class="mb-8 flex min-w-0 flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
        <div class="min-w-0 flex-1 space-y-1">
            <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">{{ __('Intelligence & content') }}</h2>
            <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">{{ __('Blog traffic and engagement from GA4.') }}</p>
        </div>
        <div class="flex shrink-0 flex-col items-stretch gap-4 sm:flex-row sm:items-center lg:flex-col lg:items-end xl:flex-row xl:items-center">
            <flux:field class="w-full min-w-0 sm:w-48 lg:w-44">
                <flux:label class="sr-only">{{ __('Date range') }}</flux:label>
                <flux:select wire:model.live="periodDays" class="w-full rounded-lg text-sm">
                    <option value="7">{{ __('Last 7 days') }}</option>
                    <option value="30">{{ __('Last 30 days') }}</option>
                    <option value="90">{{ __('Last 90 days') }}</option>
                </flux:select>
            </flux:field>
            <div class="hidden shrink-0 text-teal-500/90 dark:text-teal-400/90 sm:block lg:hidden xl:block" aria-hidden="true">
                <svg class="h-14 w-24" viewBox="0 0 96 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 44c12-18 28-26 40-30s24-4 32 2" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" opacity="0.35"/>
                    <circle cx="22" cy="26" r="3" fill="currentColor" opacity="0.5"/>
                    <circle cx="48" cy="18" r="3.5" fill="currentColor" opacity="0.65"/>
                    <circle cx="74" cy="14" r="2.5" fill="currentColor" opacity="0.45"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- KPI cards --}}
    <div class="mb-8 grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 xl:grid-cols-4">
        <article class="min-w-0 rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-950/60 dark:text-violet-300" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 3h10a2 2 0 012 2v14l-4-3-4 3-4-3-4 3V5a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" opacity="0.35"/>
                        <path d="M8 8h8M8 12h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Blog sessions') }}</p>
                    <p class="mt-1.5 text-2xl font-semibold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ number_format($snapshot['traffic_totals']['sessions'] ?? 0) }}</p>
                    <p class="mt-2 text-xs leading-snug text-zinc-400 dark:text-zinc-500">{{ __('Sessions on /intelligence') }}</p>
                </div>
            </div>
        </article>

        <article class="min-w-0 rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-950/60 dark:text-sky-300" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="9" cy="7" r="3" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                        <path d="M4 20v-1a4 4 0 014-4h2a4 4 0 014 4v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="17" cy="9" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Blog users') }}</p>
                    <p class="mt-1.5 text-2xl font-semibold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ number_format($snapshot['traffic_totals']['users'] ?? 0) }}</p>
                    <p class="mt-2 text-xs leading-snug text-zinc-400 dark:text-zinc-500">{{ __('Unique users on blog paths') }}</p>
                </div>
            </div>
        </article>

        <article class="min-w-0 rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-950/60 dark:text-amber-300" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 12h16M12 4v16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.25"/>
                        <path d="M8 16l4-8 4 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Blog → lead rate') }}</p>
                    <p class="mt-1.5 text-2xl font-semibold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ $blogToLeadPct }}%</p>
                    <p class="mt-2 text-xs leading-snug text-zinc-400 dark:text-zinc-500">{{ __('Leads ÷ blog sessions') }}</p>
                </div>
            </div>
        </article>

        <article class="min-w-0 rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-300" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.5 4.5l6.38 14.25 2.12-5.66 5.66-2.12L4.5 4.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M13.5 13.5L20 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.45"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('CTA clicks') }}</p>
                    <p class="mt-1.5 text-2xl font-semibold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ number_format($snapshot['cta_clicks']['events'] ?? 0) }}</p>
                    <p class="mt-2 text-xs leading-snug text-zinc-400 dark:text-zinc-500">{{ __('event: cta_click') }}</p>
                </div>
            </div>
        </article>
    </div>

    {{-- Blog traffic trend --}}
    <section class="mb-8 min-w-0 space-y-3">
        <div class="flex min-w-0 flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between sm:gap-4">
            <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Blog traffic trend') }}</h3>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Sessions over time (blog paths)') }}</p>
        </div>
        <div class="overflow-hidden rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50 sm:p-5">
            <div class="relative h-52 w-full min-w-0 max-w-full sm:h-60" wire:ignore.self>
                <canvas
                    id="blogTrendChart"
                    data-labels='@json($snapshot['blog_trend']['labels'] ?? [])'
                    data-values='@json($snapshot['blog_trend']['sessions'] ?? [])'
                ></canvas>
            </div>
        </div>
    </section>

    {{-- Top posts --}}
    <section class="min-w-0 space-y-3">
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Top posts') }}</h3>
        <div class="overflow-hidden rounded-lg border border-zinc-200/90 bg-white shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="min-w-0 overflow-x-auto overscroll-x-contain [-webkit-overflow-scrolling:touch]">
                <table class="w-full min-w-[640px] table-fixed text-left text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 bg-zinc-50/90 text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                            <th class="w-[40%] px-3 py-3 sm:px-5">{{ __('Title') }}</th>
                            <th class="w-[14%] px-2 py-3 sm:px-4">{{ __('Views') }}</th>
                            <th class="w-[23%] px-2 py-3 sm:px-4">{{ __('Avg. time (s)') }}</th>
                            <th class="w-[23%] px-2 py-3 sm:px-4">{{ __('Bounce %') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($snapshot['top_posts'] ?? [] as $row)
                            <tr class="transition-colors hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40">
                                <td class="min-w-0 px-3 py-3 align-top sm:px-5">
                                    <div class="line-clamp-2 text-sm font-medium leading-snug text-zinc-900 dark:text-zinc-100">{{ $row['title'] }}</div>
                                    <div class="mt-0.5 break-all font-mono text-xs text-zinc-500 dark:text-zinc-400">{{ $row['path'] }}</div>
                                </td>
                                <td class="whitespace-nowrap px-2 py-3 tabular-nums text-zinc-700 dark:text-zinc-300 sm:px-4">{{ number_format($row['views']) }}</td>
                                <td class="whitespace-nowrap px-2 py-3 tabular-nums text-zinc-700 dark:text-zinc-300 sm:px-4">{{ number_format($row['avg_time'], 1) }}</td>
                                <td class="whitespace-nowrap px-2 py-3 tabular-nums text-zinc-700 dark:text-zinc-300 sm:px-4">{{ number_format($row['bounce_rate'], 1) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-14 sm:px-6">
                                    <div class="mx-auto flex max-w-sm flex-col items-center text-center">
                                        <svg class="mb-4 h-20 w-24 text-zinc-300 dark:text-zinc-600" viewBox="0 0 96 80" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M24 68V24l12-8h24l12 8v44H24z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" opacity="0.45"/>
                                            <path d="M36 32h24M36 40h18M36 48h20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.35"/>
                                            <rect x="52" y="12" width="28" height="36" rx="2" stroke="currentColor" stroke-width="1.5" opacity="0.35"/>
                                        </svg>
                                        <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">{{ __('No data available in this period.') }}</p>
                                        <p class="mt-1 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">{{ __('When GA reports blog page metrics, they will appear here.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
(function () {
    function readDataset(canvas) {
        try {
            return {
                labels: JSON.parse(canvas.dataset.labels || '[]'),
                values: JSON.parse(canvas.dataset.values || '[]').map(Number),
            };
        } catch (e) {
            return { labels: [], values: [] };
        }
    }

    var gridColor = 'rgba(113, 113, 122, 0.12)';
    var tickColor = '#71717a';

    function initBlogChart() {
        if (typeof Chart === 'undefined') return;
        var c = document.getElementById('blogTrendChart');
        if (!c) return;
        if (window.__blogChart) {
            window.__blogChart.destroy();
            window.__blogChart = null;
        }
        var narrow = typeof window !== 'undefined' && window.innerWidth < 640;
        var d = readDataset(c);
        window.__blogChart = new Chart(c, {
            type: 'line',
            data: {
                labels: d.labels,
                datasets: [{
                    label: '{{ __("Sessions") }}',
                    data: d.values,
                    borderColor: 'rgb(20 184 166)',
                    backgroundColor: 'rgba(20, 184, 166, 0.08)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: tickColor, font: { size: 11 } },
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: tickColor,
                            font: { size: narrow ? 10 : 11 },
                            maxRotation: narrow ? 50 : 0,
                            autoSkip: true,
                            maxTicksLimit: narrow ? 6 : 12,
                        },
                    },
                },
            },
        });
    }

    document.addEventListener('DOMContentLoaded', initBlogChart);
    document.addEventListener('livewire:navigated', initBlogChart);
    document.addEventListener('livewire:init', function () {
        Livewire.hook('morph.updated', function () {
            setTimeout(initBlogChart, 50);
        });
    });
})();
</script>
@endpush
