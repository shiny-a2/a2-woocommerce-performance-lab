<?php

final class A2_Sample_Autoload_Auditor {
    private wpdb $db;

    public function __construct(wpdb $db) {
        $this->db = $db;
    }

    public function largest_autoloaded_options(int $limit = 20): array {
        $limit = max(5, min(50, $limit));

        return $this->db->get_results(
            $this->db->prepare(
                "SELECT option_name, LENGTH(option_value) AS bytes
                 FROM {$this->db->options}
                 WHERE autoload = 'yes'
                 ORDER BY bytes DESC
                 LIMIT %d",
                $limit
            ),
            ARRAY_A
        ) ?: array();
    }

    public function mark_transient_family_non_autoload(string $prefix): int {
        $like = $this->db->esc_like('_transient_' . sanitize_key($prefix)) . '%';

        return (int) $this->db->query(
            $this->db->prepare(
                "UPDATE {$this->db->options}
                 SET autoload = 'no'
                 WHERE autoload = 'yes' AND option_name LIKE %s",
                $like
            )
        );
    }
}

