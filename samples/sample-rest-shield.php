<?php

final class A2_Sample_REST_Shield {
    private array $allowed_namespaces = array('wc/store', 'wp/v2');

    public function boot(): void {
        add_filter('rest_pre_dispatch', array($this, 'maybe_short_circuit'), 9, 3);
    }

    public function maybe_short_circuit($result, WP_REST_Server $server, WP_REST_Request $request) {
        if (is_user_logged_in() || current_user_can('manage_options')) {
            return $result;
        }

        $route = trim($request->get_route(), '/');
        $namespace = strtok($route, '/');

        if ($namespace && in_array($namespace, $this->allowed_namespaces, true)) {
            return $result;
        }

        if ($this->is_known_expensive_anonymous_route($route)) {
            return new WP_REST_Response(array('ok' => false, 'reason' => 'route_not_available_for_anonymous_requests'), 403);
        }

        return $result;
    }

    private function is_known_expensive_anonymous_route(string $route): bool {
        $patterns = array(
            '#^elementor/#',
            '#^wc-analytics/#',
            '#^wc-admin/#',
        );

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $route)) {
                return true;
            }
        }

        return false;
    }
}

