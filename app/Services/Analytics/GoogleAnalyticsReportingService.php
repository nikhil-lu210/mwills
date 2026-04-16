<?php

namespace App\Services\Analytics;

use App\Support\AnalyticsCredentialsPath;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\Filter\StringFilter;
use Google\Analytics\Data\V1beta\Filter\StringFilter\MatchType;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;
use Google\Analytics\Data\V1beta\RunRealtimeReportRequest;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\RunReportResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class GoogleAnalyticsReportingService
{
    private ?BetaAnalyticsDataClient $client = null;

    public function __construct(
        private ?string $propertyId,
        private ?string $credentialsPath,
        private int $cacheTtlSeconds,
        private string $blogPathContains,
    ) {}

    public function isConfigured(): bool
    {
        return $this->propertyId !== null
            && $this->propertyId !== ''
            && $this->credentialsPath !== null
            && $this->credentialsPath !== ''
            && is_readable($this->resolveCredentialsPath());
    }

    protected function resolveCredentialsPath(): string
    {
        $path = $this->credentialsPath ?? '';

        if ($path === '') {
            return '';
        }

        if (str_starts_with($path, '/') || preg_match('#^[A-Za-z]:\\\\#', $path)) {
            return $path;
        }

        return AnalyticsCredentialsPath::resolveAbsolute($path);
    }

    protected function client(): ?BetaAnalyticsDataClient
    {
        if (! $this->isConfigured()) {
            return null;
        }

        if ($this->client === null) {
            $this->client = new BetaAnalyticsDataClient([
                'credentials' => $this->resolveCredentialsPath(),
            ]);
        }

        return $this->client;
    }

    /**
     * Drop the cached API client (e.g. after credentials change in settings).
     */
    public function clearCachedClient(): void
    {
        $this->client = null;
    }

    protected function property(string $suffix = ''): string
    {
        return 'properties/'.$this->propertyId.$suffix;
    }

    /**
     * @return array{start: string, end: string}
     */
    protected function dateRange(int $days): array
    {
        $d = max(1, min(366, $days));

        return [
            'start' => $d.'daysAgo',
            'end' => 'today',
        ];
    }

    public function getDashboardSnapshot(int $periodDays): array
    {
        $cacheKey = 'analytics.dashboard.'.$periodDays;

        return Cache::remember($cacheKey, $this->cacheTtlSeconds, function () use ($periodDays) {
            return [
                'overview' => $this->fetchOverview($periodDays),
                'new_vs_returning' => $this->fetchNewVsReturning($periodDays),
                'traffic_trend' => $this->fetchTrafficTrend($periodDays),
                'traffic_sources' => $this->fetchTrafficSources($periodDays),
                'devices' => $this->fetchDevices($periodDays),
                'top_pages' => $this->fetchTopPages($periodDays),
                'funnel' => $this->fetchFunnelSteps($periodDays),
                'traffic_totals' => $this->fetchTrafficTotals($periodDays),
                'blog_traffic' => $this->fetchBlogTraffic($periodDays),
                'realtime' => $this->fetchRealtimeUsers(),
                'cta_clicks' => $this->fetchCtaClicks($periodDays),
            ];
        });
    }

    public function getContentSnapshot(int $periodDays): array
    {
        $cacheKey = 'analytics.content.'.$periodDays;

        return Cache::remember($cacheKey, $this->cacheTtlSeconds, function () use ($periodDays) {
            return [
                'blog_trend' => $this->fetchBlogTrafficTrend($periodDays),
                'traffic_totals' => $this->fetchBlogTraffic($periodDays),
                'top_posts' => $this->fetchTopBlogPosts($periodDays),
                'cta_clicks' => $this->fetchCtaClicks($periodDays),
            ];
        });
    }

    /**
     * @return array{total_users: int, sessions: int, sessions_blog: int}
     */
    protected function fetchTrafficTotals(int $days): array
    {
        $overview = $this->fetchOverview($days);
        $blog = $this->fetchBlogTraffic($days);

        return [
            'total_users' => $overview['total_users'],
            'sessions' => $overview['sessions'],
            'sessions_blog' => $blog['sessions'],
        ];
    }

    /**
     * @return array{sessions: int, users: int}
     */
    protected function fetchBlogTraffic(int $days): array
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name' => 'pagePath',
                'string_filter' => new StringFilter([
                    'match_type' => MatchType::CONTAINS,
                    'value' => $this->blogPathContains,
                    'case_sensitive' => false,
                ]),
            ]),
        ]);

        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'metrics' => [
                new Metric(['name' => 'sessions']),
                new Metric(['name' => 'totalUsers']),
            ],
            'dimension_filter' => $filter,
        ]));

        if ($r === null) {
            return ['sessions' => 0, 'users' => 0];
        }

        $row = $r->getRows()[0] ?? null;

        return [
            'sessions' => (int) ($row?->getMetricValues()[0]?->getValue() ?? 0),
            'users' => (int) ($row?->getMetricValues()[1]?->getValue() ?? 0),
        ];
    }

    /**
     * @return array{total_users: int, sessions: int, page_views: int}
     */
    protected function fetchOverview(int $days): array
    {
        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'metrics' => [
                new Metric(['name' => 'totalUsers']),
                new Metric(['name' => 'sessions']),
                new Metric(['name' => 'screenPageViews']),
            ],
        ]));

        if ($r === null) {
            return ['total_users' => 0, 'sessions' => 0, 'page_views' => 0];
        }

        $row = $r->getRows()[0] ?? null;

        return [
            'total_users' => (int) ($row?->getMetricValues()[0]?->getValue() ?? 0),
            'sessions' => (int) ($row?->getMetricValues()[1]?->getValue() ?? 0),
            'page_views' => (int) ($row?->getMetricValues()[2]?->getValue() ?? 0),
        ];
    }

    /**
     * @return array<int, array{label: string, users: int}>
     */
    protected function fetchNewVsReturning(int $days): array
    {
        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'dimensions' => [new Dimension(['name' => 'newVsReturning'])],
            'metrics' => [new Metric(['name' => 'totalUsers'])],
        ]));

        if ($r === null) {
            return [];
        }

        $out = [];
        foreach ($r->getRows() as $row) {
            $label = $row->getDimensionValues()[0]?->getValue() ?? '—';
            $out[] = [
                'label' => $label,
                'users' => (int) ($row->getMetricValues()[0]?->getValue() ?? 0),
            ];
        }

        return $out;
    }

    /**
     * @return array{labels: string[], sessions: int[]}
     */
    protected function fetchTrafficTrend(int $days): array
    {
        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'dimensions' => [new Dimension(['name' => 'date'])],
            'metrics' => [new Metric(['name' => 'sessions'])],
            'order_bys' => [
                new OrderBy([
                    'dimension' => new DimensionOrderBy([
                        'dimension_name' => 'date',
                    ]),
                    'desc' => false,
                ]),
            ],
        ]));

        if ($r === null) {
            return ['labels' => [], 'sessions' => []];
        }

        $labels = [];
        $sessions = [];
        foreach ($r->getRows() as $row) {
            $raw = $row->getDimensionValues()[0]?->getValue() ?? '';
            $labels[] = $this->formatGaDateLabel($raw);
            $sessions[] = (int) ($row->getMetricValues()[0]?->getValue() ?? 0);
        }

        return ['labels' => $labels, 'sessions' => $sessions];
    }

    /**
     * @return array{labels: string[], sessions: int[]}
     */
    protected function fetchBlogTrafficTrend(int $days): array
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name' => 'pagePath',
                'string_filter' => new StringFilter([
                    'match_type' => MatchType::CONTAINS,
                    'value' => $this->blogPathContains,
                    'case_sensitive' => false,
                ]),
            ]),
        ]);

        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'dimensions' => [new Dimension(['name' => 'date'])],
            'metrics' => [new Metric(['name' => 'sessions'])],
            'dimension_filter' => $filter,
            'order_bys' => [
                new OrderBy([
                    'dimension' => new DimensionOrderBy([
                        'dimension_name' => 'date',
                    ]),
                    'desc' => false,
                ]),
            ],
        ]));

        if ($r === null) {
            return ['labels' => [], 'sessions' => []];
        }

        $labels = [];
        $sessions = [];
        foreach ($r->getRows() as $row) {
            $raw = $row->getDimensionValues()[0]?->getValue() ?? '';
            $labels[] = $this->formatGaDateLabel($raw);
            $sessions[] = (int) ($row->getMetricValues()[0]?->getValue() ?? 0);
        }

        return ['labels' => $labels, 'sessions' => $sessions];
    }

    protected function formatGaDateLabel(string $yyyymmdd): string
    {
        if (strlen($yyyymmdd) === 8) {
            $dt = \DateTimeImmutable::createFromFormat('Ymd', $yyyymmdd);

            return $dt ? $dt->format('M j') : $yyyymmdd;
        }

        return $yyyymmdd;
    }

    /**
     * @return array<int, array{label: string, sessions: int}>
     */
    protected function fetchTrafficSources(int $days): array
    {
        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'dimensions' => [new Dimension(['name' => 'sessionDefaultChannelGroup'])],
            'metrics' => [new Metric(['name' => 'sessions'])],
            'order_bys' => [
                new OrderBy([
                    'metric' => new MetricOrderBy(['metric_name' => 'sessions']),
                    'desc' => true,
                ]),
            ],
            'limit' => 8,
        ]));

        if ($r === null) {
            return [];
        }

        $out = [];
        foreach ($r->getRows() as $row) {
            $out[] = [
                'label' => $row->getDimensionValues()[0]?->getValue() ?? '—',
                'sessions' => (int) ($row->getMetricValues()[0]?->getValue() ?? 0),
            ];
        }

        return $out;
    }

    /**
     * @return array<int, array{label: string, users: int}>
     */
    protected function fetchDevices(int $days): array
    {
        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'dimensions' => [new Dimension(['name' => 'deviceCategory'])],
            'metrics' => [new Metric(['name' => 'totalUsers'])],
        ]));

        if ($r === null) {
            return [];
        }

        $out = [];
        foreach ($r->getRows() as $row) {
            $out[] = [
                'label' => ucfirst($row->getDimensionValues()[0]?->getValue() ?? '—'),
                'users' => (int) ($row->getMetricValues()[0]?->getValue() ?? 0),
            ];
        }

        return $out;
    }

    /**
     * @return array<int, array{path: string, title: string, views: int, avg_time: float, bounce_rate: float}>
     */
    protected function fetchTopPages(int $days): array
    {
        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'dimensions' => [
                new Dimension(['name' => 'pagePath']),
                new Dimension(['name' => 'pageTitle']),
            ],
            'metrics' => [
                new Metric(['name' => 'screenPageViews']),
                new Metric(['name' => 'averageSessionDuration']),
                new Metric(['name' => 'bounceRate']),
            ],
            'order_bys' => [
                new OrderBy([
                    'metric' => new MetricOrderBy(['metric_name' => 'screenPageViews']),
                    'desc' => true,
                ]),
            ],
            'limit' => 12,
        ]));

        if ($r === null) {
            return [];
        }

        $out = [];
        foreach ($r->getRows() as $row) {
            $out[] = [
                'path' => $row->getDimensionValues()[0]?->getValue() ?? '',
                'title' => $row->getDimensionValues()[1]?->getValue() ?? '',
                'views' => (int) ($row->getMetricValues()[0]?->getValue() ?? 0),
                'avg_time' => round((float) ($row->getMetricValues()[1]?->getValue() ?? 0), 1),
                'bounce_rate' => round((float) ($row->getMetricValues()[2]?->getValue() ?? 0) * 100, 1),
            ];
        }

        return $out;
    }

    /**
     * @return array<int, array{path: string, title: string, views: int, avg_time: float, bounce_rate: float}>
     */
    protected function fetchTopBlogPosts(int $days): array
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name' => 'pagePath',
                'string_filter' => new StringFilter([
                    'match_type' => MatchType::CONTAINS,
                    'value' => $this->blogPathContains,
                    'case_sensitive' => false,
                ]),
            ]),
        ]);

        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'dimensions' => [
                new Dimension(['name' => 'pagePath']),
                new Dimension(['name' => 'pageTitle']),
            ],
            'metrics' => [
                new Metric(['name' => 'screenPageViews']),
                new Metric(['name' => 'averageSessionDuration']),
                new Metric(['name' => 'bounceRate']),
            ],
            'dimension_filter' => $filter,
            'order_bys' => [
                new OrderBy([
                    'metric' => new MetricOrderBy(['metric_name' => 'screenPageViews']),
                    'desc' => true,
                ]),
            ],
            'limit' => 15,
        ]));

        if ($r === null) {
            return [];
        }

        $out = [];
        foreach ($r->getRows() as $row) {
            $out[] = [
                'path' => $row->getDimensionValues()[0]?->getValue() ?? '',
                'title' => $row->getDimensionValues()[1]?->getValue() ?? '',
                'views' => (int) ($row->getMetricValues()[0]?->getValue() ?? 0),
                'avg_time' => round((float) ($row->getMetricValues()[1]?->getValue() ?? 0), 1),
                'bounce_rate' => round((float) ($row->getMetricValues()[2]?->getValue() ?? 0) * 100, 1),
            ];
        }

        return $out;
    }

    /**
     * @return array<int, array{label: string, views: int}>
     */
    protected function fetchFunnelSteps(int $days): array
    {
        $steps = [
            ['label' => __('Homepage'), 'contains' => null, 'exact' => '/'],
            ['label' => __('Services'), 'contains' => '/services', 'exact' => null],
            ['label' => __('Contact'), 'contains' => null, 'exact' => '/contact'],
            ['label' => __('Thank you'), 'contains' => 'contact/thank-you', 'exact' => null],
        ];

        $out = [];
        foreach ($steps as $step) {
            $filter = null;
            if ($step['exact'] !== null) {
                $filter = new FilterExpression([
                    'filter' => new Filter([
                        'field_name' => 'pagePath',
                        'string_filter' => new StringFilter([
                            'match_type' => MatchType::EXACT,
                            'value' => $step['exact'],
                            'case_sensitive' => false,
                        ]),
                    ]),
                ]);
            } elseif ($step['contains'] !== null) {
                $filter = new FilterExpression([
                    'filter' => new Filter([
                        'field_name' => 'pagePath',
                        'string_filter' => new StringFilter([
                            'match_type' => MatchType::CONTAINS,
                            'value' => $step['contains'],
                            'case_sensitive' => false,
                        ]),
                    ]),
                ]);
            }

            $r = $this->runReport(new RunReportRequest([
                'property' => $this->property(),
                'date_ranges' => [new DateRange([
                    'start_date' => $this->dateRange($days)['start'],
                    'end_date' => $this->dateRange($days)['end'],
                ])],
                'metrics' => [new Metric(['name' => 'screenPageViews'])],
                'dimension_filter' => $filter,
            ]));

            $views = 0;
            if ($r !== null && $r->getRows()->count() > 0) {
                $views = (int) ($r->getRows()[0]->getMetricValues()[0]?->getValue() ?? 0);
            }

            $out[] = ['label' => $step['label'], 'views' => $views];
        }

        return $out;
    }

    /**
     * @return array{active_users: int}
     */
    protected function fetchRealtimeUsers(): array
    {
        $client = $this->client();
        if ($client === null) {
            return ['active_users' => 0];
        }

        try {
            $req = new RunRealtimeReportRequest([
                'property' => $this->property(),
                'metrics' => [new Metric(['name' => 'activeUsers'])],
            ]);
            $response = $client->runRealtimeReport($req);
            $row = $response->getRows()[0] ?? null;
            $v = (int) ($row?->getMetricValues()[0]?->getValue() ?? 0);

            return ['active_users' => $v];
        } catch (Throwable $e) {
            Log::warning('GA4 realtime report failed', ['error' => $e->getMessage()]);

            return ['active_users' => 0];
        }
    }

    /**
     * @return array{events: int}
     */
    protected function fetchCtaClicks(int $days): array
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name' => 'eventName',
                'string_filter' => new StringFilter([
                    'match_type' => MatchType::EXACT,
                    'value' => 'cta_click',
                    'case_sensitive' => false,
                ]),
            ]),
        ]);

        $r = $this->runReport(new RunReportRequest([
            'property' => $this->property(),
            'date_ranges' => [new DateRange([
                'start_date' => $this->dateRange($days)['start'],
                'end_date' => $this->dateRange($days)['end'],
            ])],
            'metrics' => [new Metric(['name' => 'eventCount'])],
            'dimension_filter' => $filter,
        ]));

        if ($r === null) {
            return ['events' => 0];
        }

        $row = $r->getRows()[0] ?? null;

        return ['events' => (int) ($row?->getMetricValues()[0]?->getValue() ?? 0)];
    }

    protected function runReport(RunReportRequest $request): ?RunReportResponse
    {
        $client = $this->client();
        if ($client === null) {
            return null;
        }

        try {
            return $client->runReport($request);
        } catch (Throwable $e) {
            Log::warning('GA4 runReport failed', ['error' => $e->getMessage()]);

            return null;
        }
    }

    public static function fromConfig(): self
    {
        return new self(
            propertyId: config('analytics.property_id'),
            credentialsPath: config('analytics.credentials_path'),
            cacheTtlSeconds: (int) config('analytics.cache_ttl_seconds', 300),
            blogPathContains: (string) config('analytics.blog_path_contains', 'intelligence'),
        );
    }
}
