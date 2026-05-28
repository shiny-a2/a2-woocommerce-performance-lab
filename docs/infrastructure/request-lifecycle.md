# Request Lifecycle

This showcase treats WooCommerce performance as a request-classification problem before it treats it as a caching problem.

## Path

```mermaid
flowchart TD
    A[Incoming HTTP request] --> B[Normalize method, route, auth, cookies]
    B --> C{Commerce state?}
    C -->|cart checkout account order-pay logged-in POST| W[Bypass optimization path]
    C -->|guest PDP/archive/search| D[Classify public page request]
    C -->|REST/API| R[Classify route pressure]
    D --> E{Cache eligible?}
    E -->|yes| F{Fresh microcache entry?}
    F -->|hit| H[Return cached response]
    F -->|miss| I[Render WooCommerce safely]
    I --> J[Store short-lived entry]
    E -->|no| W
    R --> K{Known safe route?}
    K -->|yes| W
    K -->|anonymous pressure| L[Rate limit, short-circuit, or cached response]
    W --> M[Slow request probe]
    H --> M
    L --> M
```

## Operating Notes

- Cache decisions happen after request classification, not before it.
- Commerce state wins over speed. Cart, checkout, account, order payment, authenticated, and write requests bypass public-page cache behavior.
- Bot/crawler/user differentiation should be conservative. Unknown agents should not receive unsafe cached commerce responses.
- REST pressure is handled separately from page rendering because anonymous API traffic can create hidden backend load.
- External HTTP calls should fail fast on frontend paths. A slow provider should not hold the main product page request open.

## Bypass Conditions

- unsafe HTTP methods;
- logged-in or privileged requests;
- cart, checkout, account, order payment, and payment callback routes;
- nonce-sensitive admin or AJAX paths;
- requests with session/cart cookies;
- routes where private state can affect output.

