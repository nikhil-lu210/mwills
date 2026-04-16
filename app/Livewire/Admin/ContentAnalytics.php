<?php

namespace App\Livewire\Admin;

use App\Models\ConsultationMessage;
use App\Services\Analytics\GoogleAnalyticsReportingService;
use Livewire\Component;

class ContentAnalytics extends Component
{
    public int $periodDays = 30;

    public function updatedPeriodDays(mixed $value): void
    {
        $v = (int) $value;
        $this->periodDays = in_array($v, [7, 30, 90], true) ? $v : 30;
    }

    public function render()
    {
        $analytics = app(GoogleAnalyticsReportingService::class);

        $snapshot = $analytics->isConfigured()
            ? $analytics->getContentSnapshot($this->periodDays)
            : [
                'blog_trend' => ['labels' => [], 'sessions' => []],
                'traffic_totals' => ['sessions' => 0, 'users' => 0],
                'top_posts' => [],
                'cta_clicks' => ['events' => 0],
            ];

        $blogSessions = (int) ($snapshot['traffic_totals']['sessions'] ?? 0);
        $leadsAfterBlog = ConsultationMessage::query()
            ->where('created_at', '>=', now()->subDays($this->periodDays)->startOfDay())
            ->count();

        $blogToLeadPct = $blogSessions > 0 ? round(($leadsAfterBlog / $blogSessions) * 100, 2) : 0.0;

        return view('livewire.admin.analytics.content', [
            'snapshot' => $snapshot,
            'blogToLeadPct' => $blogToLeadPct,
            'gaConfigured' => $analytics->isConfigured(),
        ])->layout('layouts.app.sidebar', [
            'title' => __('Content analytics'),
            'breadcrumbs' => [
                ['label' => __('Dashboard'), 'href' => route('dashboard')],
                ['label' => __('Content analytics'), 'href' => null],
            ],
        ]);
    }
}
