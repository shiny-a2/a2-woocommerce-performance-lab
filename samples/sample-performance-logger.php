<?php

final class A2_Sample_Performance_Logger {
    private float $started_at = 0.0;

    public function boot(): void {
        $this->started_at = microtime(true);
        add_action('shutdown', array($this, 'log'), PHP_INT_MAX);
    }

    public function log(): void {
        $duration_ms = (int) round((microtime(true) - $this->started_at) * 1000);

        if ($duration_ms < 800 || is_user_logged_in()) {
            return;
        }

        $entry = array(
            'time' => gmdate('c'),
            'route' => sanitize_text_field(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'),
            'method' => sanitize_text_field($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            'duration_ms' => $duration_ms,
            'queries' => function_exists('get_num_queries') ? get_num_queries() : null,
            'status' => http_response_code(),
        );

        error_log('[a2-performance-sample] ' . wp_json_encode($entry));
    }
}

