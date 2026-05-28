<?php
/**
 * Plugin Name: A2 Performance Lab Sample
 * Description: Public-safe bootstrap example for modular WooCommerce performance guards.
 */

if (!defined('ABSPATH')) {
    exit;
}

final class A2_Performance_Lab_Bootstrap {
    private array $modules = array();

    public function register(string $name, callable $factory): void {
        $this->modules[$name] = $factory;
    }

    public function boot(): void {
        foreach ($this->modules as $factory) {
            $module = $factory();

            if (is_object($module) && method_exists($module, 'boot')) {
                $module->boot();
            }
        }
    }
}

$a2_performance_lab = new A2_Performance_Lab_Bootstrap();

add_action('plugins_loaded', static function () use ($a2_performance_lab): void {
    if (!class_exists('WooCommerce')) {
        return;
    }

    $a2_performance_lab->boot();
}, 20);

