<?php

declare(strict_types=1);

namespace A2\Showcase\Performance\Infrastructure;

final class ExternalHttpFailFastPolicy
{
    public function optionsForRoute(string $routeClass): array
    {
        $timeout = in_array($routeClass, array('guest-catalog', 'standard-page'), true) ? 1.2 : 3.0;

        return array(
            'timeout_seconds' => $timeout,
            'redirection_limit' => 0,
            'blocking' => true,
            'safe_to_degrade' => $timeout < 2.0,
        );
    }

    public function shouldDegrade(array $result): bool
    {
        return ($result['timed_out'] ?? false) || (int) ($result['status_code'] ?? 0) >= 500;
    }
}
