<?php

final class A2_Sample_Microcache {
    private string $cache_dir;
    private int $ttl;

    public function __construct(string $cache_dir, int $ttl = 60) {
        $this->cache_dir = rtrim($cache_dir, '/');
        $this->ttl = max(10, $ttl);
    }

    public function boot(): void {
        add_action('template_redirect', array($this, 'maybe_serve'), 0);
        add_action('shutdown', array($this, 'maybe_store'), 0);
    }

    public function maybe_serve(): void {
        if (!$this->is_cacheable_request()) {
            return;
        }

        $file = $this->path_for_request();
        if (!is_readable($file) || filemtime($file) + $this->ttl < time()) {
            ob_start();
            return;
        }

        header('X-A2-Sample-Cache: HIT');
        readfile($file);
        exit;
    }

    public function maybe_store(): void {
        if (!$this->is_cacheable_request() || !ob_get_level()) {
            return;
        }

        $html = ob_get_clean();
        if (!is_string($html) || strlen($html) < 512 || http_response_code() !== 200) {
            echo $html;
            return;
        }

        wp_mkdir_p($this->cache_dir);
        file_put_contents($this->path_for_request(), $html, LOCK_EX);
        header('X-A2-Sample-Cache: MISS');
        echo $html;
    }

    private function is_cacheable_request(): bool {
        if (is_admin() || is_user_logged_in() || strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
            return false;
        }

        if (function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page())) {
            return false;
        }

        return function_exists('is_product') && (is_product() || is_shop() || is_product_category());
    }

    private function path_for_request(): string {
        $key = md5(home_url(add_query_arg(array(), $_SERVER['REQUEST_URI'] ?? '/')));
        return $this->cache_dir . '/' . $key . '.html';
    }
}

