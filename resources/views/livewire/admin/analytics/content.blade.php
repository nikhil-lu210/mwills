@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
@endpush

<div class="space-y-8 w-full max-w-7xl" wire:key="content-{{ $periodDays }}">
    @if(!$gaConfigured)
        <flux:callout variant="warning" icon="exclamation-triangle">
            {{ __('Configure Google Analytics under Site settings to see content metrics.') }}
        </flux:callout>
    @endif

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="lg">{{ __('Intelligence & content') }}</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Blog traffic and engagement from GA4.') }}</flux:text>
        </div>
        <flux:field class="w-full sm:w-48">
            <flux:label class="sr-only">{{ __('Date range') }}</flux:label>
            <flux:select wire:model.live="periodDays">
                <option value="7">{{ __('Last 7 days') }}</option>
                <option value="30">{{ __('Last 30 days') }}</option>
                <option value="90">{{ __('Last 90 days') }}</option>
            </flux:select>
        </flux:field>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <flux:card class="p-5 border border-zinc-200 dark:border-zinc-700">
            <flux:text class="text-xs font-medium uppercase tracking-wide text-zinc-500">{{ __('Blog sessions') }}</flux:text>
            <flux:heading class="mt-2 text-2xl tabular-nums">{{ number_format($snapshot['traffic_totals']['sessions'] ?? 0) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5 border border-zinc-200 dark:border-zinc-700">
            <flux:text class="text-xs font-medium uppercase tracking-wide text-zinc-500">{{ __('Blog users') }}</flux:text>
            <flux:heading class="mt-2 text-2xl tabular-nums">{{ number_format($snapshot['traffic_totals']['users'] ?? 0) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5 border border-zinc-200 dark:border-zinc-700">
            <flux:text class="text-xs font-medium uppercase tracking-wide text-zinc-500">{{ __('Blog → lead rate') }}</flux:text>
            <flux:heading class="mt-2 text-2xl tabular-nums">{{ $blogToLeadPct }}%</flux:heading>
            <flux:text class="mt-1 text-xs text-zinc-500">{{ __('Leads ÷ blog sessions') }}</flux:text>
        </flux:card>
        <flux:card class="p-5 border border-zinc-200 dark:border-zinc-700">
            <flux:text class="text-xs font-medium uppercase tracking-wide text-zinc-500">{{ __('CTA clicks') }}</flux:text>
            <flux:heading class="mt-2 text-2xl tabular-nums">{{ number_format($snapshot['cta_clicks']['events'] ?? 0) }}</flux:heading>
            <flux:text class="mt-1 text-xs text-zinc-500">{{ __('event: cta_click') }}</flux:text>
        </flux:card>
    </div>

    <flux:card class="p-6 border border-zinc-200 dark:border-zinc-700">
        <flux:heading size="sm" class="mb-4">{{ __('Blog traffic trend') }}</flux:heading>
        <div class="h-72" wire:ignore.self>
            <canvas
                id="blogTrendChart"
                data-labels='@json($snapshot['blog_trend']['labels'] ?? [])'
                data-values='@json($snapshot['blog_trend']['sessions'] ?? [])'
            ></canvas>
        </div>
    </flux:card>

    <flux:card class="overflow-hidden border border-zinc-200 dark:border-zinc-700">
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
            <flux:heading size="sm">{{ __('Top posts') }}</flux:heading>
        </div>
        <div class="min-w-0 overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>{{ __('Title') }}</flux:table.column>
                    <flux:table.column>{{ __('Views') }}</flux:table.column>
                    <flux:table.column>{{ __('Avg. time (s)') }}</flux:table.column>
                    <flux:table.column>{{ __('Bounce %') }}</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @forelse($snapshot['top_posts'] ?? [] as $row)
                        <flux:table.row>
                            <flux:table.cell>
                                <div class="font-medium line-clamp-2">{{ $row['title'] }}</div>
                                <div class="text-xs font-mono text-zinc-500 truncate max-w-md">{{ $row['path'] }}</div>
                            </flux:table.cell>
                            <flux:table.cell class="tabular-nums">{{ number_format($row['views']) }}</flux:table.cell>
                            <flux:table.cell class="tabular-nums">{{ number_format($row['avg_time'], 1) }}</flux:table.cell>
                            <flux:table.cell class="tabular-nums">{{ number_format($row['bounce_rate'], 1) }}%</flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="py-8 text-center text-zinc-500">{{ __('No data') }}</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>
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
    function initBlogChart() {
        if (typeof Chart === 'undefined') return;
        var c = document.getElementById('blogTrendChart');
        if (!c) return;
        if (window.__blogChart) window.__blogChart.destroy();
        var d = readDataset(c);
        window.__blogChart = new Chart(c, {
            type: 'line',
            data: {
                labels: d.labels,
                datasets: [{
                    label: '{{ __("Sessions") }}',
                    data: d.values,
                    borderColor: 'rgb(245 158 11)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: true,
                    tension: 0.3,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { display: false } },
                },
            },
        });
    }
    document.addEventListener('DOMContentLoaded', initBlogChart);
    document.addEventListener('livewire:navigated', initBlogChart);
})();
</script>
@endpush
