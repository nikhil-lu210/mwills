@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
@endpush

@php
    $trendClass = fn (?float $pct) => $pct === null ? 'text-zinc-400 dark:text-zinc-500' : ($pct >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400');
@endphp

<div class="mx-auto w-full min-w-0 max-w-7xl space-y-8 sm:space-y-10" wire:key="dash-{{ $periodDays }}">
    @if(!$gaConfigured)
        <flux:callout variant="warning" icon="exclamation-triangle" class="rounded-xl break-words">
            {{ __('Google Analytics is not fully configured. Open Site settings → Google Analytics, add your Measurement ID, Property ID, and service account JSON key. KPIs below still use leads from your database.') }}
        </flux:callout>
    @endif

    {{-- Page header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="space-y-1">
            <flux:heading size="lg" class="tracking-tight">{{ __('Overview') }}</flux:heading>
            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Performance for the selected period.') }}</flux:text>
        </div>
        <flux:field class="w-full sm:w-44 shrink-0">
            <flux:label class="sr-only">{{ __('Date range') }}</flux:label>
            <flux:select wire:model.live="periodDays" class="rounded-lg">
                <option value="7">{{ __('Last 7 days') }}</option>
                <option value="30">{{ __('Last 30 days') }}</option>
                <option value="90">{{ __('Last 90 days') }}</option>
            </flux:select>
        </flux:field>
    </div>

    {{-- KPI mini cards: sm:2 md:3 lg:5 --}}
    <div class="grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
        {{-- Visitors — violet --}}
        <div class="group rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-violet-100 to-violet-50 text-violet-600 shadow-inner dark:from-violet-900/50 dark:to-violet-900/20 dark:text-violet-300" aria-hidden="true">
                    <svg class="size-[22px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="9" cy="7" r="3" stroke="currentColor" stroke-width="1.5" opacity="0.35"/>
                        <path d="M4 20v-1a4 4 0 014-4h2a4 4 0 014 4v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="17" cy="9" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M21 20v-.5a3 3 0 00-3-3h-1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.6"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Visitors') }}</p>
                    <p class="mt-1 text-xl font-bold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ number_format($snapshot['overview']['total_users'] ?? 0) }}</p>
                    <p class="mt-2 text-[11px] text-zinc-400 dark:text-zinc-500">{{ __('Unique users') }}</p>
                </div>
            </div>
        </div>

        {{-- New vs returning — sky --}}
        <div class="group rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-sky-100 to-sky-50 text-sky-600 shadow-inner dark:from-sky-900/50 dark:to-sky-900/20 dark:text-sky-300" aria-hidden="true">
                    <svg class="size-[22px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 16l-3-3m0 0l3-3m-3 3h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.45"/>
                        <circle cx="12" cy="8" r="2.5" fill="currentColor" opacity="0.2"/>
                        <path d="M17 8l3 3m0 0l-3 3m3-3H5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Audience') }}</p>
                    @if(count($snapshot['new_vs_returning'] ?? []) > 0)
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach($snapshot['new_vs_returning'] as $row)
                                <span class="inline-flex items-center gap-1 rounded-md bg-zinc-50 px-2 py-0.5 text-[11px] font-medium text-zinc-700 ring-1 ring-zinc-200/80 dark:bg-zinc-800/80 dark:text-zinc-200 dark:ring-zinc-600">
                                    <span class="max-w-[5rem] truncate text-zinc-500 dark:text-zinc-400">{{ $row['label'] }}</span>
                                    <span class="tabular-nums text-zinc-900 dark:text-zinc-100">{{ number_format($row['users']) }}</span>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-1 text-xl font-bold tabular-nums text-zinc-300 dark:text-zinc-600">—</p>
                    @endif
                    <p class="mt-2 text-[11px] text-zinc-400 dark:text-zinc-500">{{ __('New vs returning') }}</p>
                </div>
            </div>
        </div>

        {{-- Leads — emerald --}}
        <div class="group rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-600 shadow-inner dark:from-emerald-900/50 dark:to-emerald-900/20 dark:text-emerald-300" aria-hidden="true">
                    <svg class="size-[22px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.5" opacity="0.35"/>
                        <path d="M3 9l9 5 9-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Leads') }}</p>
                    <p class="mt-1 text-xl font-bold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ number_format($leadsCount) }}</p>
                    <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-0.5">
                        <p class="text-[11px] text-zinc-400 dark:text-zinc-500">{{ __('Form submissions') }}</p>
                        @if($leadsTrendPct !== null)
                            <span class="text-[11px] font-medium tabular-nums {{ $trendClass($leadsTrendPct) }}">{{ $leadsTrendPct >= 0 ? '+' : '' }}{{ $leadsTrendPct }}%</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Conversion — amber --}}
        <div class="group rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 text-amber-600 shadow-inner dark:from-amber-900/50 dark:to-amber-900/20 dark:text-amber-300" aria-hidden="true">
                    <svg class="size-[22px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 19V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.35"/>
                        <path d="M4 15h3l2-4 3 8 2-6h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Conversion') }}</p>
                    <p class="mt-1 text-xl font-bold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ $conversionRate }}%</p>
                    <p class="mt-2 text-[11px] text-zinc-400 dark:text-zinc-500">{{ __('Leads ÷ sessions') }}</p>
                </div>
            </div>
        </div>

        {{-- Blog traffic — rose --}}
        <div class="group rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-rose-100 to-rose-50 text-rose-600 shadow-inner dark:from-rose-900/50 dark:to-rose-900/20 dark:text-rose-300" aria-hidden="true">
                    <svg class="size-[22px]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 3h10a2 2 0 012 2v14l-4-3-4 3-4-3-4 3V5a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" opacity="0.35"/>
                        <path d="M8 8h8M8 12h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Blog share') }}</p>
                    <p class="mt-1 text-xl font-bold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ $blogTrafficPct }}%</p>
                    <p class="mt-2 text-[11px] text-zinc-400 dark:text-zinc-500">{{ __('Sessions on /intelligence') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Traffic trend --}}
    <section class="min-w-0 space-y-3">
        <div class="flex min-w-0 flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between sm:gap-4">
            <flux:heading size="sm" class="text-zinc-800 dark:text-zinc-100">{{ __('Traffic trend') }}</flux:heading>
            <flux:text class="shrink-0 text-xs text-zinc-500">{{ __('Sessions over time') }}</flux:text>
        </div>
        <div class="rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-5">
            <div class="relative h-48 w-full min-w-0 max-w-full sm:h-56" wire:ignore.self>
                <canvas
                    id="trafficTrendChart"
                    data-labels='@json($snapshot['traffic_trend']['labels'] ?? [])'
                    data-values='@json($snapshot['traffic_trend']['sessions'] ?? [])'
                ></canvas>
            </div>
        </div>
    </section>

    {{-- Sources + devices --}}
    <section class="min-w-0 space-y-3">
        <flux:heading size="sm" class="text-zinc-800 dark:text-zinc-100">{{ __('Breakdown') }}</flux:heading>
        <div class="grid min-w-0 grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-6">
            <div class="min-w-0 rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-5">
                <flux:text class="mb-3 text-xs font-medium text-zinc-500">{{ __('Traffic sources') }}</flux:text>
                <div class="relative h-44 w-full min-w-0 max-w-full sm:h-52">
                    <canvas
                        id="sourcesChart"
                        data-labels='@json(array_column($snapshot['traffic_sources'] ?? [], 'label'))'
                        data-values='@json(array_column($snapshot['traffic_sources'] ?? [], 'sessions'))'
                    ></canvas>
                </div>
            </div>
            <div class="min-w-0 rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-5">
                <flux:text class="mb-3 text-xs font-medium text-zinc-500">{{ __('Devices') }}</flux:text>
                <div class="relative h-44 w-full min-w-0 max-w-full sm:h-52">
                    <canvas
                        id="devicesChart"
                        data-labels='@json(array_column($snapshot['devices'] ?? [], 'label'))'
                        data-values='@json(array_column($snapshot['devices'] ?? [], 'users'))'
                    ></canvas>
                </div>
            </div>
        </div>
    </section>

    {{-- Top pages table --}}
    <section class="min-w-0 space-y-3">
        <flux:heading size="sm" class="text-zinc-800 dark:text-zinc-100">{{ __('Top pages') }}</flux:heading>
        <div class="max-w-full overflow-hidden rounded-xl border border-zinc-200/80 bg-white shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/40">
            <div class="min-w-0 overflow-x-auto overscroll-x-contain [-webkit-overflow-scrolling:touch]">
                <table class="w-full min-w-0 table-fixed text-left text-xs md:table-auto md:min-w-[640px] md:text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200/90 bg-zinc-50/90 text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                            <th class="w-[40%] px-3 py-2.5 sm:px-5 md:w-auto">{{ __('URL') }}</th>
                            <th class="w-[18%] px-2 py-2.5 sm:px-5 md:w-auto">{{ __('Views') }}</th>
                            <th class="w-[21%] px-2 py-2.5 sm:px-5 md:w-auto">{{ __('Avg. time (s)') }}</th>
                            <th class="w-[21%] px-2 py-2.5 sm:px-5 md:w-auto">{{ __('Bounce %') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($snapshot['top_pages'] ?? [] as $row)
                            <tr class="transition-colors odd:bg-white even:bg-zinc-50/50 hover:bg-violet-50/60 dark:odd:bg-zinc-900/20 dark:even:bg-zinc-900/40 dark:hover:bg-zinc-800/70">
                                <td class="px-3 py-2.5 align-top sm:px-5">
                                    <div class="break-all font-mono text-[11px] leading-snug text-zinc-800 dark:text-zinc-200 sm:text-xs md:truncate" title="{{ $row['path'] }}">{{ $row['path'] }}</div>
                                    <div class="mt-0.5 line-clamp-2 text-[11px] text-zinc-500 sm:text-xs md:truncate">{{ $row['title'] }}</div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2.5 tabular-nums text-zinc-700 dark:text-zinc-300 sm:px-5">{{ number_format($row['views']) }}</td>
                                <td class="whitespace-nowrap px-4 py-2.5 tabular-nums text-zinc-700 dark:text-zinc-300 sm:px-5">{{ number_format($row['avg_time'], 1) }}</td>
                                <td class="whitespace-nowrap px-4 py-2.5 tabular-nums text-zinc-700 dark:text-zinc-300 sm:px-5">{{ number_format($row['bounce_rate'], 1) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-zinc-500 sm:px-5">{{ __('No data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Funnel + realtime --}}
    <section class="min-w-0 space-y-3">
        <flux:heading size="sm" class="text-zinc-800 dark:text-zinc-100">{{ __('Funnel & live') }}</flux:heading>
        <div class="grid min-w-0 grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-6">
            <div class="min-w-0 rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-5 lg:col-span-2">
                <flux:text class="mb-1 text-xs font-medium text-zinc-500">{{ __('Conversion funnel') }}</flux:text>
                <flux:text class="mb-4 text-xs text-zinc-400 dark:text-zinc-500">{{ __('Page views by step (path-based estimate).') }}</flux:text>
                <div class="space-y-2.5">
                    @foreach($snapshot['funnel'] ?? [] as $step)
                        <div>
                            <div class="mb-1 flex justify-between gap-2 text-xs sm:text-sm">
                                <span class="text-zinc-700 dark:text-zinc-200">{{ $step['label'] }}</span>
                                <span class="tabular-nums font-medium text-zinc-900 dark:text-zinc-100">{{ number_format($step['views']) }}</span>
                            </div>
                            <div class="h-1.5 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                                @php($max = max(1, collect($snapshot['funnel'] ?? [])->max('views')))
                                <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500" style="width: {{ min(100, ($step['views'] / $max) * 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="min-w-0 rounded-xl border border-zinc-200/80 bg-white p-3 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/40 sm:p-5">
                <flux:text class="mb-1 text-xs font-medium text-zinc-500">{{ __('Real-time') }}</flux:text>
                <p class="text-3xl font-bold tabular-nums tracking-tight text-violet-600 dark:text-violet-400">{{ number_format($snapshot['realtime']['active_users'] ?? 0) }}</p>
                <flux:text class="mt-1 text-xs text-zinc-500">{{ __('Active users (~30 min)') }}</flux:text>
                <div class="mt-4 rounded-lg bg-zinc-50 px-3 py-2 text-xs text-zinc-600 dark:bg-zinc-800/80 dark:text-zinc-300">
                    <span class="text-zinc-500">{{ __('CTA clicks') }}</span>
                    <span class="ml-1 font-semibold tabular-nums text-zinc-900 dark:text-zinc-100">{{ number_format($snapshot['cta_clicks']['events'] ?? 0) }}</span>
                </div>
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

    function destroyChart(key) {
        if (window.__dashCharts && window.__dashCharts[key]) {
            window.__dashCharts[key].destroy();
            window.__dashCharts[key] = null;
        }
    }

    window.__dashCharts = window.__dashCharts || {};

    var gridColor = 'rgba(113, 113, 122, 0.12)';
    var tickColor = '#71717a';

    function initDashboardCharts() {
        if (typeof Chart === 'undefined') return;

        var narrow = typeof window !== 'undefined' && window.innerWidth < 640;

        var line = document.getElementById('trafficTrendChart');
        if (line) {
            destroyChart('line');
            var d = readDataset(line);
            window.__dashCharts.line = new Chart(line, {
                type: 'line',
                data: {
                    labels: d.labels,
                    datasets: [{
                        label: '{{ __("Sessions") }}',
                        data: d.values,
                        borderColor: 'rgb(139 92 246)',
                        backgroundColor: 'rgba(139, 92, 246, 0.06)',
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

        var src = document.getElementById('sourcesChart');
        if (src) {
            destroyChart('src');
            var s = readDataset(src);
            window.__dashCharts.src = new Chart(src, {
                type: 'doughnut',
                data: {
                    labels: s.labels,
                    datasets: [{
                        data: s.values,
                        borderWidth: 0,
                        backgroundColor: ['#8b5cf6', '#64748b', '#a855f7', '#34d399', '#60a5fa', '#fbbf24', '#f472b6', '#2dd4bf'],
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: narrow ? 4 : 8 },
                    cutout: narrow ? '58%' : '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: narrow ? 8 : 10,
                                padding: narrow ? 8 : 12,
                                font: { size: narrow ? 10 : 11 },
                            },
                        },
                    },
                },
            });
        }

        var dev = document.getElementById('devicesChart');
        if (dev) {
            destroyChart('dev');
            var dv = readDataset(dev);
            window.__dashCharts.dev = new Chart(dev, {
                type: 'bar',
                data: {
                    labels: dv.labels,
                    datasets: [{
                        label: '{{ __("Users") }}',
                        data: dv.values,
                        backgroundColor: 'rgba(139, 92, 246, 0.45)',
                        borderRadius: 6,
                        borderSkipped: false,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor },
                            ticks: { color: tickColor, font: { size: 11 } },
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: tickColor, font: { size: 11 } },
                        },
                    },
                },
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initDashboardCharts);
    document.addEventListener('livewire:navigated', initDashboardCharts);
    document.addEventListener('livewire:init', function () {
        Livewire.hook('morph.updated', function () {
            setTimeout(initDashboardCharts, 50);
        });
    });
})();
</script>
@endpush
