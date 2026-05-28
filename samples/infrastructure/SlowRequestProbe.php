<?php

declare(strict_types=1);

namespace A2\Showcase\Performance\Infrastructure;

final class SlowRequestProbe
{
    public function __construct(private int $thresholdMs = 900)
    {
    }

    public function summarize(string $routeClass, float $startedAt, int $statusCode, int $queryCount): ?array
    {
        $elapsedMs = (int) round((microtime(true) - $startedAt) * 1000);

        if ($elapsedMs < $this->thresholdMs) {
            return null;
        }

        return array(
            'route_class' => $routeClass,
            'elapsed_ms' => $elapsedMs,
            'status_code' => $statusCode,
            'query_bucket' => $this->queryBucket($queryCount),
        );
    }

    private function queryBucket(int $queryCount): string
    {
        return match (true) {
            $queryCount < 50 => 'low',
            $queryCount < 150 => 'medium',
            default => 'high',
        };
    }
}
