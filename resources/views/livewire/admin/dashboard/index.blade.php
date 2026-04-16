@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
@endpush

@php
    $trendClass = fn (?float $pct) => $pct === null ? 'text-zinc-400 dark:text-zinc-500' : ($pct >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400');
    $devices = $snapshot['devices'] ?? [];
    $deviceUserTotal = max(1, (int) collect($devices)->sum('users'));
    $funnelSteps = $snapshot['funnel'] ?? [];
    $funnelCount = max(1, count($funnelSteps));
    $funnelMaxViews = max(1, (int) collect($funnelSteps)->max('views'));
@endphp

<div
    class="mx-auto w-full min-w-0 max-w-full overflow-x-hidden"
    wire:key="dash-{{ $periodDays }}"
>
    @if(!$gaConfigured)
        <flux:callout variant="warning" icon="exclamation-triangle" class="mb-6 rounded-lg break-words text-sm leading-relaxed">
            {{ __('Google Analytics is not fully configured. Open Site settings → Analytics, add your Measurement ID, Property ID, and service account JSON key. KPIs below still use leads from your database.') }}
        </flux:callout>
    @endif

    {{-- Overview header --}}
    <div class="mb-8 flex min-w-0 flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
        <div class="min-w-0 flex-1 space-y-1">
            <h2 class="text-base font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">{{ __('Overview') }}</h2>
            <p class="text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">{{ __('Performance for the selected period.') }}</p>
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
    <div class="mb-8 grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 xl:grid-cols-5">
        <article class="min-w-0 rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-violet-600 dark:bg-violet-950/60 dark:text-violet-300" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="9" cy="7" r="3" stroke="currentColor" stroke-width="1.5" opacity="0.4"/>
                        <path d="M4 20v-1a4 4 0 014-4h2a4 4 0 014 4v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <circle cx="17" cy="9" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Visitors') }}</p>
                    <p class="mt-1.5 text-2xl font-semibold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ number_format($snapshot['overview']['total_users'] ?? 0) }}</p>
                    <p class="mt-2 text-xs leading-snug text-zinc-400 dark:text-zinc-500">{{ __('Unique users') }}</p>
                </div>
            </div>
        </article>

        <article class="min-w-0 rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-sky-100 text-sky-600 dark:bg-sky-950/60 dark:text-sky-300" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 16l-3-3m0 0l3-3m-3 3h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.45"/>
                        <path d="M17 8l3 3m0 0l-3 3m3-3H5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Audience') }}</p>
                    @if(count($snapshot['new_vs_returning'] ?? []) > 0)
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach($snapshot['new_vs_returning'] as $row)
                                <span class="inline-flex max-w-full items-center gap-1 rounded-md bg-zinc-50 px-2 py-0.5 text-xs font-medium text-zinc-800 ring-1 ring-zinc-200/80 dark:bg-zinc-800/80 dark:text-zinc-200 dark:ring-zinc-600">
                                    <span class="max-w-[6rem] truncate text-zinc-500 dark:text-zinc-400">{{ $row['label'] }}</span>
                                    <span class="tabular-nums text-zinc-900 dark:text-zinc-100">{{ number_format($row['users']) }}</span>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-1.5 text-2xl font-semibold tabular-nums text-zinc-300 dark:text-zinc-600">—</p>
                    @endif
                    <p class="mt-2 text-xs leading-snug text-zinc-400 dark:text-zinc-500">{{ __('New vs returning') }}</p>
                </div>
            </div>
        </article>

        <article class="min-w-0 rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-300" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.5" opacity="0.35"/>
                        <path d="M3 9l9 5 9-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Leads') }}</p>
                    <p class="mt-1.5 text-2xl font-semibold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ number_format($leadsCount) }}</p>
                    <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-0.5">
                        <p class="text-xs text-zinc-400 dark:text-zinc-500">{{ __('Form submissions') }}</p>
                        @if($leadsTrendPct !== null)
                            <span class="text-xs font-medium tabular-nums {{ $trendClass($leadsTrendPct) }}">{{ $leadsTrendPct >= 0 ? '+' : '' }}{{ $leadsTrendPct }}%</span>
                        @endif
                    </div>
                </div>
            </div>
        </article>

        <article class="min-w-0 rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-950/60 dark:text-amber-300" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 19V5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.35"/>
                        <path d="M4 15h3l2-4 3 8 2-6h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Conversion') }}</p>
                    <p class="mt-1.5 text-2xl font-semibold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ $conversionRate }}%</p>
                    <p class="mt-2 text-xs leading-snug text-zinc-400 dark:text-zinc-500">{{ __('Leads ÷ sessions') }}</p>
                </div>
            </div>
        </article>

        <article class="min-w-0 rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-950/60 dark:text-rose-300" aria-hidden="true">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 3h10a2 2 0 012 2v14l-4-3-4 3-4-3-4 3V5a2 2 0 012-2z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" opacity="0.35"/>
                        <path d="M8 8h8M8 12h5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">{{ __('Blog share') }}</p>
                    <p class="mt-1.5 text-2xl font-semibold tabular-nums leading-none tracking-tight text-zinc-900 dark:text-zinc-50">{{ $blogTrafficPct }}%</p>
                    <p class="mt-2 text-xs leading-snug text-zinc-400 dark:text-zinc-500">{{ __('Sessions on /intelligence') }}</p>
                </div>
            </div>
        </article>
    </div>

    {{-- Traffic trend --}}
    <section class="mb-8 min-w-0 space-y-3">
        <div class="flex min-w-0 flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between sm:gap-4">
            <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Traffic trend') }}</h3>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('Sessions over time') }}</p>
        </div>
        <div class="overflow-hidden rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50 sm:p-5">
            <div class="relative h-52 w-full min-w-0 max-w-full sm:h-60" wire:ignore.self>
                <canvas
                    id="trafficTrendChart"
                    data-labels='@json($snapshot['traffic_trend']['labels'] ?? [])'
                    data-values='@json($snapshot['traffic_trend']['sessions'] ?? [])'
                ></canvas>
            </div>
        </div>
    </section>

    {{-- Breakdown --}}
    <section class="mb-8 min-w-0 space-y-3">
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Breakdown') }}</h3>
        <div class="grid min-w-0 grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-6">
            <div class="min-w-0 overflow-hidden rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50 sm:p-5">
                <p class="mb-4 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('Traffic sources') }}</p>
                <div class="relative mx-auto h-48 w-full min-w-0 max-w-full sm:h-56">
                    <canvas
                        id="sourcesChart"
                        data-labels='@json(array_column($snapshot['traffic_sources'] ?? [], 'label'))'
                        data-values='@json(array_column($snapshot['traffic_sources'] ?? [], 'sessions'))'
                    ></canvas>
                </div>
            </div>
            <div class="min-w-0 overflow-hidden rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50 sm:p-5">
                <p class="mb-4 text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('Devices') }}</p>
                @if(count($devices) > 0)
                    <ul class="space-y-4">
                        @foreach($devices as $row)
                            @php
                                $pct = min(100, round(($row['users'] / $deviceUserTotal) * 100, 1));
                            @endphp
                            <li class="min-w-0">
                                <div class="mb-1.5 flex items-center justify-between gap-2 text-xs sm:text-sm">
                                    <span class="min-w-0 truncate font-medium text-zinc-700 dark:text-zinc-200">{{ $row['label'] }}</span>
                                    <span class="shrink-0 tabular-nums text-zinc-500 dark:text-zinc-400">{{ number_format($row['users']) }}</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-teal-500 to-teal-500/90 transition-[width] duration-500 ease-out dark:from-teal-600 dark:to-teal-500"
                                        style="width: {{ $pct }}%"
                                    ></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">{{ __('No device data for this period.') }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Top pages --}}
    <section class="mb-8 min-w-0 space-y-3">
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Top pages') }}</h3>
        <div class="overflow-hidden rounded-lg border border-zinc-200/90 bg-white shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50">
            <div class="min-w-0 overflow-x-auto overscroll-x-contain [-webkit-overflow-scrolling:touch]">
                <table class="w-full min-w-[640px] table-fixed text-left text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 bg-zinc-50/90 text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-400">
                            <th class="w-[38%] px-3 py-3 sm:px-5">{{ __('URL') }}</th>
                            <th class="w-[14%] px-2 py-3 sm:px-4">{{ __('Views') }}</th>
                            <th class="w-[24%] px-2 py-3 sm:px-4">{{ __('Avg. time (s)') }}</th>
                            <th class="w-[24%] px-2 py-3 sm:px-4">{{ __('Bounce %') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($snapshot['top_pages'] ?? [] as $row)
                            <tr class="transition-colors hover:bg-zinc-50/80 dark:hover:bg-zinc-800/40">
                                <td class="min-w-0 px-3 py-3 align-top sm:px-5">
                                    <div class="break-all font-mono text-xs leading-snug text-zinc-800 dark:text-zinc-200 sm:text-[13px]">{{ $row['path'] }}</div>
                                    @if(!empty($row['title']))
                                        <div class="mt-0.5 line-clamp-2 text-xs text-zinc-500 dark:text-zinc-400">{{ $row['title'] }}</div>
                                    @endif
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
                                        <p class="mt-1 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">{{ __('When GA reports page metrics, they will appear here.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Funnel & realtime --}}
    <section class="min-w-0 space-y-3">
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Funnel & live') }}</h3>
        <div class="grid min-w-0 grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-6">
            <div class="min-w-0 overflow-hidden rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50 sm:p-5 lg:col-span-2">
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('Conversion funnel') }}</p>
                <p class="mt-1 text-xs leading-relaxed text-zinc-400 dark:text-zinc-500">{{ __('Page views by step (path-based estimate).') }}</p>
                <div class="mt-6 space-y-2">
                    @foreach($funnelSteps as $idx => $step)
                        @php
                            $barWidth = min(100, ($step['views'] / $funnelMaxViews) * 100);
                            $stepWidthPercent = max(38, 100 - ($idx * (55 / max(1, $funnelCount - 1))));
                        @endphp
                        <div class="flex flex-col items-center gap-2">
                            <div
                                class="rounded-lg border border-amber-200/80 bg-gradient-to-b from-amber-50 to-amber-100/90 px-3 py-2.5 sm:px-4 dark:border-amber-900/40 dark:from-amber-950/50 dark:to-amber-900/20"
                                style="width: {{ $stepWidthPercent }}%; max-width: 100%;"
                            >
                                <div class="flex items-center justify-between gap-2 text-xs font-medium text-amber-950 dark:text-amber-100 sm:text-sm">
                                    <span class="min-w-0 truncate">{{ $step['label'] }}</span>
                                    <span class="shrink-0 tabular-nums">{{ number_format($step['views']) }}</span>
                                </div>
                                <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-amber-200/60 dark:bg-amber-900/50">
                                    <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500" style="width: {{ $barWidth }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="min-w-0 overflow-hidden rounded-lg border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-700/80 dark:bg-zinc-900/50 sm:p-5">
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ __('Real-time') }}</p>
                <p class="mt-3 text-4xl font-semibold tabular-nums tracking-tight text-violet-600 dark:text-violet-400">{{ number_format($snapshot['realtime']['active_users'] ?? 0) }}</p>
                <p class="mt-1 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">{{ __('Active users (~30 min)') }}</p>
                <div class="mt-6 rounded-lg border border-zinc-200/80 bg-zinc-50 px-3 py-2.5 text-xs dark:border-zinc-700 dark:bg-zinc-800/60">
                    <span class="text-zinc-500 dark:text-zinc-400">{{ __('CTA clicks') }}</span>
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
