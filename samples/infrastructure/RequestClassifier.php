<?php

declare(strict_types=1);

namespace A2\Showcase\Performance\Infrastructure;

final class RequestClassifier
{
    public function classify(array $request): string
    {
        $method = strtoupper((string) ($request['method'] ?? 'GET'));
        $path = trim((string) ($request['path'] ?? ''), '/');
        $loggedIn = (bool) ($request['logged_in'] ?? false);

        if ($method !== 'GET' || $loggedIn) {
            return 'unsafe-bypass';
        }

        if ($this->matches($path, array('cart', 'checkout', 'my-account', 'order-pay'))) {
            return 'commerce-bypass';
        }

        if (str_starts_with($path, 'wp-json/')) {
            return 'rest-route';
        }

        if ($this->matches($path, array('product/', 'product-category/', 'shop'))) {
            return 'guest-catalog';
        }

        return 'standard-page';
    }

    private function matches(string $path, array $needles): bool
    {
        foreach ($needles as $needle) {
            if ($path === trim($needle, '/') || str_starts_with($path, trim($needle, '/') . '/')) {
                return true;
            }
        }

        return false;
    }
}
