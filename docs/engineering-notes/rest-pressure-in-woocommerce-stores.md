# REST Pressure In WooCommerce Stores

## Problem

REST/API traffic can create hidden backend pressure. It may not appear as a slow product page, but it still consumes PHP, database, and plugin bootstrap time.

## Context

Elementor, WooCommerce admin, analytics, Store API, search integrations, and bots can all touch REST routes. Some calls are legitimate. Some are noise. Some are valid but too expensive for anonymous users.

## Constraint

REST shielding must not break admin workflows, authenticated integrations, or required public endpoints.

## Decision

Classify REST routes by user state, namespace, and known cost:

- authenticated/admin traffic passes;
- known public endpoints stay available;
- expensive anonymous routes can be short-circuited;
- logging stays coarse and privacy-safe.

## Tradeoff

The shield needs ongoing review because plugins change their REST behavior. A static blocklist can become wrong.

## Failure Mode

The failure is blocking legitimate integrations while trying to reduce bot or anonymous noise.

## What I Would Improve Next

Add a review mode that records candidate routes before enforcing the shield.

