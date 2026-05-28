# Known Limitations

- Cache invalidation examples are intentionally partial. Real stores need product, taxonomy, Elementor, stock, price, and promotion invalidation rules.
- REST shield samples show route-classification structure, not a complete security policy.
- Performance logger samples avoid real log storage details and retention policy.
- Cleanup samples are conservative and do not include production scheduling thresholds.
- No screenshots are included yet because admin diagnostics can easily expose private route names or business data.

## Operational Edge Cases To Track

- Logged-out users with cart cookies.
- Payment callback and order-pay routes.
- Elementor preview/editor requests.
- Object-cache behavior on hosts with unusual drop-ins.
- Action Scheduler tables on stores with very large historical queues.

