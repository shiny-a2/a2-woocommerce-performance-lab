<?php

final class A2_Sample_Action_Scheduler_Cleanup {
    private const HOOK = 'a2_sample_action_scheduler_cleanup';

    public function boot(): void {
        add_action(self::HOOK, array($this, 'run'));

        if (function_exists('as_next_scheduled_action') && !as_next_scheduled_action(self::HOOK)) {
            as_schedule_recurring_action(time() + 300, HOUR_IN_SECONDS, self::HOOK);
        }
    }

    public function run(): void {
        global $wpdb;

        $table = $wpdb->prefix . 'actionscheduler_actions';
        $batch = 500;

        $ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT action_id FROM {$table} WHERE status IN ('complete','canceled','failed') AND scheduled_date_gmt < %s ORDER BY action_id ASC LIMIT %d",
                gmdate('Y-m-d H:i:s', time() - 14 * DAY_IN_SECONDS),
                $batch
            )
        );

        if (!$ids) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '%d'));
        $wpdb->query($wpdb->prepare("DELETE FROM {$table} WHERE action_id IN ({$placeholders})", array_map('intval', $ids)));
    }
}

