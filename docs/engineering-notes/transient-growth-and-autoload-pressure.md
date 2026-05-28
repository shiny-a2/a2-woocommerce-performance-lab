# Transient Growth And Autoload Pressure

## Problem

Transient growth can look harmless until it starts affecting WordPress bootstrap. When option rows grow too much, every request can inherit cost from data that was supposed to be temporary.

## Context

WooCommerce and its plugin ecosystem use transients heavily. Some transient families are normal. Others grow because product loops, recommendation modules, filters, or third-party plugins create too many keys.

## Constraint

Deleting everything is not a strategy. Some transients protect performance. Some are expensive to rebuild. Some should not autoload.

## Decision

Handle transient pressure as database hygiene:

- identify large families;
- clean in chunks;
- avoid autoload for transient-heavy rows;
- watch for repeated growth after cleanup.

## Tradeoff

Cleanup reduces table pressure but may increase short-term recomputation. That is why the cleanup should be measured and reversible.

## Failure Mode

The failure is a loop: clean the table, trigger expensive rebuilds, recreate the same transient storm, and learn nothing.

## What I Would Improve Next

Track transient family growth over time so cleanup decisions are based on recurrence, not one large snapshot.

