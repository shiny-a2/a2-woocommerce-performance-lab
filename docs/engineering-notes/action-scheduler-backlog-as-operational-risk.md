# Action Scheduler Backlog As Operational Risk

## Problem

Action Scheduler backlog is often treated as housekeeping. In WooCommerce, it can become operational risk: delayed jobs, slow admin screens, unreliable follow-up tasks, and confusing status for staff.

## Context

Large queues usually come from repeated plugin jobs, failed retries, imports, webhooks, analytics tasks, or cleanup work that never catches up. The queue is not only a database table; it is a deferred workload system.

## Constraint

Cleanup must not create a new spike. A broad delete or aggressive batch can compete with the same store it is trying to protect.

## Decision

Use bounded cleanup and reduce upstream pressure where possible:

- small batches;
- recurring maintenance;
- clear age/status filters;
- no deletion of active pending work without review.

## Tradeoff

Bounded cleanup is slower. It is also safer. Production maintenance should be predictable more than dramatic.

## Failure Mode

The common failure is cleaning symptoms while the job source keeps producing work faster than the queue drains.

## What I Would Improve Next

Add a dashboard that separates "old completed/failed rows" from "currently growing pending workload" so the operator can see pressure, not just table size.

