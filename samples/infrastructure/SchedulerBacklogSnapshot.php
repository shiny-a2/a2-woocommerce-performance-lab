<?php

declare(strict_types=1);

namespace A2\Showcase\Performance\Infrastructure;

final class SchedulerBacklogSnapshot
{
    public function __construct(
        public readonly int $pendingCount,
        public readonly ?int $oldestPendingAgeMinutes,
        public readonly string $capturedAt
    ) {
    }

    public function riskLevel(): string
    {
        if ($this->pendingCount > 25000 || ($this->oldestPendingAgeMinutes ?? 0) > 240) {
            return 'high';
        }

        if ($this->pendingCount > 5000 || ($this->oldestPendingAgeMinutes ?? 0) > 60) {
            return 'watch';
        }

        return 'normal';
    }
}
