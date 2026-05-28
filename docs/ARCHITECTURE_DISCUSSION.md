# Architecture Discussion

The main decision in this case study is to classify requests before optimizing them. WooCommerce performance work becomes risky when cache code runs before the system understands whether the request is public, session-bound, checkout-sensitive, or admin-related.

The public sample keeps the rules conservative. That means it will miss some cacheable traffic, but it reduces the chance of caching personalized or checkout-sensitive output.

## Open Questions

- How much cache metadata should be logged without creating new write pressure?
- Which invalidation events belong in the cache layer versus product-update hooks?
- Should queue cleanup run through Action Scheduler itself or a separate WP-Cron command?

