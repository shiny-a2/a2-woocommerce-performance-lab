# Request Classification Before Cache

## Problem

WooCommerce cache work can make a store faster and less correct at the same time. The dangerous mistake is treating every `GET` request as public just because it looks cacheable.

## Context

Product pages and archives are good cache candidates. Cart, checkout, account, order-pay, payment callbacks, Elementor preview, and logged-in sessions are not. Some requests sit in the middle: they look public but carry WooCommerce session cookies.

## Constraint

The first job is not caching. The first job is deciding whether the request is safe to optimize.

## Decision

Classify requests before cache lookup:

- unsafe commerce state exits early;
- logged-in and session-bound requests bypass;
- public PDP/archive requests can use short-lived cache;
- diagnostics record request class, not customer details.

## Tradeoff

This conservative approach leaves some cacheable traffic uncached. That is acceptable. Serving the wrong commerce state is worse than missing a cache hit.

## Failure Mode

The failure I want to avoid is not "cache miss." It is cached cart fragments, wrong customer state, or stale checkout-sensitive output.

## What I Would Improve Next

Add a small request-classification fixture so route/cookie cases can be reviewed without touching production.

