# Failure Mode Matrix

| Failure mode | Likely cause | Detection signal | Safe behavior | Recovery path | What not to do |
| --- | --- | --- | --- | --- | --- |
| Cache stampede | Many guests miss the same entry | Concurrent misses and high render time | Use lock and stale fallback where safe | Short TTL, rebuild lock, inspect invalidation | Cache unsafe commerce pages |
| Transient explosion | Plugin or job writes too many temporary rows | Options table growth and bootstrap delay | Batch cleanup with limits | Identify transient family and drain slowly | Delete broadly without review |
| REST route pressure | Anonymous API traffic hits expensive paths | REST p95 and request volume rise | Rate control or short-circuit anonymous pressure | Add route policy and cached snapshot | Block legitimate integrations blindly |
| Action Scheduler backlog | Jobs enqueue faster than drain | Pending count and oldest age rise | Keep admin usable and cleanup bounded | Reduce enqueue source, drain in batches | Run unbounded cleanup on live traffic |
| External HTTP timeout | Provider or remote endpoint is slow | Timeout count and frontend wait time | Fail fast and degrade non-critical output | Lower timeout and move work async | Let provider waits block PDP render |

