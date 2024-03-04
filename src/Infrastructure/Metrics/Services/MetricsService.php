<?php

declare(strict_types=1);

namespace ToniLiesche\Roadrunner\Infrastructure\Metrics\Services;

use Spiral\RoadRunner\Metrics\MetricsInterface;

readonly final class MetricsService
{
    public function __construct(private MetricsInterface $metricsClient)
    {
    }

    public function trackRequest(string $route, string $method, int $status): void
    {
        $this->metricsClient->observe('http_requests', 1, [$route, $method, (string)$status]);
    }
}
