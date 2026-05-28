# Observability And Instrumentation

The useful signals are the ones that explain pressure without storing private request details.

## Measure

- route class, not full private URL;
- response time and slow request count;
- cache hit, miss, bypass, and stale behavior;
- REST route class and block/allow decision;
- Action Scheduler pending count and oldest pending age;
- transient cleanup batch size and duration;
- external HTTP timeout count;
- database query count buckets.

## Why It Matters

WooCommerce stores can be slow for several reasons at once. Instrumentation should help separate cache misses, REST pressure, queue backlog, option growth, and external network waits.

## Do Not Log Publicly

- customer identifiers;
- order IDs;
- cart contents;
- full query strings;
- payment callback payloads;
- private domains or server paths;
- raw production logs.

## Threshold Concepts

- page render above the expected TTFB budget;
- rising bypass rate on pages expected to be cacheable;
- Action Scheduler backlog growing faster than it drains;
- transient cleanup taking longer than its safe batch window;
- repeated external HTTP timeouts on frontend routes.

## Debug Workflow

1. Identify the route class.
2. Check whether the request bypassed cache for a valid reason.
3. Compare slow-path timing with query count and REST pressure.
4. Check queue and transient pressure before changing frontend code.
5. Keep remediation reversible and module-scoped.

