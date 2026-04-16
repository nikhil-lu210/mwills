<?php

namespace App\Livewire\Admin;

use App\Models\ConsultationMessage;
use App\Services\Analytics\GoogleAnalyticsReportingService;
use Livewire\Component;

class Dashboard extends Component
{
    public int $periodDays = 7;

    public function updatedPeriodDays(mixed $value): void
    {
        $v = (int) $value;
        $this->periodDays = in_array($v, [7, 30, 90], true) ? $v : 7;
    }

    public function render()
    {
        $analytics = app(GoogleAnalyticsReportingService::class);

        $snapshot = $analytics->isConfigured()
            ? $analytics->getDashboardSnapshot($this->periodDays)
            : $this->emptySnapshot();

        $currentStart = now()->subDays($this->periodDays)->startOfDay();

        $leadsCount = ConsultationMessage::query()
            ->where('created_at', '>=', $currentStart)
            ->count();

        $prevLeadsCount = ConsultationMessage::query()
            ->where('created_at', '>=', $currentStart->copy()->subDays($this->periodDays))
            ->where('created_at', '<', $currentStart)
            ->count();

        $leadsTrendPct = $this->percentChange($leadsCount, $prevLeadsCount);

        $sessions = (int) ($snapshot['overview']['sessions'] ?? 0);
        $conversionRate = $sessions > 0 ? round(($leadsCount / $sessions) * 100, 2) : 0.0;

        $blogSessions = (int) ($snapshot['traffic_totals']['sessions_blog'] ?? 0);
        $blogTrafficPct = $sessions > 0 ? round(($blogSessions / $sessions) * 100, 1) : 0.0;

        return view('livewire.admin.dashboard.index', [
            'snapshot' => $snapshot,
            'leadsCount' => $leadsCount,
            'leadsTrendPct' => $leadsTrendPct,
            'conversionRate' => $conversionRate,
            'blogTrafficPct' => $blogTrafficPct,
            'gaConfigured' => $analytics->isConfigured(),
        ])->layout('layouts.app.sidebar', [
            'title' => __('Dashboard'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => null],
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function emptySnapshot(): array
    {
        return [
            'overview' => ['total_users' => 0, 'sessions' => 0, 'page_views' => 0],
            'new_vs_returning' => [],
            'traffic_trend' => ['labels' => [], 'sessions' => []],
            'traffic_sources' => [],
            'devices' => [],
            'top_pages' => [],
            'funnel' => [
                ['label' => __('Homepage'), 'views' => 0],
                ['label' => __('Services'), 'views' => 0],
                ['label' => __('Contact'), 'views' => 0],
                ['label' => __('Thank you'), 'views' => 0],
            ],
            'traffic_totals' => ['total_users' => 0, 'sessions' => 0, 'sessions_blog' => 0],
            'blog_traffic' => ['sessions' => 0, 'users' => 0],
            'realtime' => ['active_users' => 0],
            'cta_clicks' => ['events' => 0],
        ];
    }

    /**
     * Percent change from previous to current value (e.g. leads vs prior period).
     */
    private function percentChange(int $current, int $previous): ?float
    {
        if ($previous === 0) {
            return $current > 0 ? 100.0 : null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
