<?php

final class A2_Sample_Request_Gate {
    public function is_public_read_request(): bool {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        if ($method !== 'GET' || is_admin() || is_user_logged_in()) {
            return false;
        }

        if ($this->has_woocommerce_session()) {
            return false;
        }

        if (function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page())) {
            return false;
        }

        return function_exists('is_product') && (is_product() || is_shop() || is_product_category());
    }

    public function request_class(): string {
        if (function_exists('is_product') && is_product()) {
            return 'pdp';
        }

        if (function_exists('is_product_category') && is_product_category()) {
            return 'archive';
        }

        if (function_exists('is_shop') && is_shop()) {
            return 'shop';
        }

        return 'other';
    }

    private function has_woocommerce_session(): bool {
        foreach ($_COOKIE as $name => $value) {
            if (strpos((string) $name, 'woocommerce_') === 0 || strpos((string) $name, 'wp_woocommerce_session_') === 0) {
                return true;
            }
        }

        return false;
    }
}

