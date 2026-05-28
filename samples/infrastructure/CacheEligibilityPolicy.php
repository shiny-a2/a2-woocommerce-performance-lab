<?php

declare(strict_types=1);

namespace A2\Showcase\Performance\Infrastructure;

final class CacheEligibilityPolicy
{
    public function isEligible(string $classification, array $cookies): bool
    {
        if (! in_array($classification, array('guest-catalog', 'standard-page'), true)) {
            return false;
        }

        foreach (array_keys($cookies) as $name) {
            if (str_starts_with((string) $name, 'woocommerce_') || str_contains((string) $name, 'wp_woocommerce_session')) {
                return false;
            }
        }

        return true;
    }
}
