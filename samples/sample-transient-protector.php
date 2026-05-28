<?php

final class A2_Sample_Transient_Protector {
    private wpdb $db;
    private int $batch_size;

    public function __construct(wpdb $db, int $batch_size = 500) {
        $this->db = $db;
        $this->batch_size = max(100, min(1000, $batch_size));
    }

    public function cleanup_family(string $prefix): int {
        $prefix = sanitize_key($prefix);
        if ($prefix === '') {
            return 0;
        }

        $like = $this->db->esc_like('_transient_' . $prefix) . '%';

        $option_names = $this->db->get_col(
            $this->db->prepare(
                "SELECT option_name FROM {$this->db->options}
                 WHERE option_name LIKE %s
                 ORDER BY option_id ASC
                 LIMIT %d",
                $like,
                $this->batch_size
            )
        );

        if (!$option_names) {
            return 0;
        }

        foreach ($option_names as $name) {
            delete_option($name);
            delete_option(str_replace('_transient_', '_transient_timeout_', $name));
        }

        return count($option_names);
    }
}

