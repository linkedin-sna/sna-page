<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>Performance</h1>

<p>
Getting real applications deployed requires having simple, well understood, predictable performance. We have done a fair amount of benchmarks to understand different scenarios, but not all the results are in repeatable, scripted up form with charts and graphs. This page is meant to hold a dump of basic results until we can put together a more complete guide to understanding and tuning performance of a cluster of machines.
</p>

<p>
Note that there are a number of tunable parameters: the cache size on a node, the number of nodes you read and write to on each operation, the amount of data on a server, etc.
</p>

<h4>Estimating network latency and data/cache ratios</h4>
<p>Disk is far and away the slowest and lowest throughput operation. Disk seeks are 5-10ms and a lookup could involve multiple disk seeks. When the hot data is primarily in memory you are benchmarking the software, when it is primarily on disk you are benchmarking your disk system. When you are testing real software you want to release, it is important to understand the whole package, but when you are trying to isolate one variable it can be misleading.</p>

<p>The calculation we do when planning a feature is to take the estimated total data size, divide by the number of nodes and multiply be the replication factor. This is the amount of data per node. Then compare this to the cache size per node. This is the fraction of the total data that can be served from memory. This fraction can be compared to some estimate of the hotness of the data. For example if the requests are completely random, then a high proportion should be in memory. If instead the requests represent data about particular members, and only some fraction of members are logged in at once, and one member session indicates many requests, then you may survive with a much lower fraction.

<p>Network is the second biggest bottleneck after disk. The maximum throughput one java client can get for roundtrips through a socket to a service that does absolutely nothing seems to be about 30-40k req/sec over localhost. Adding work on the client or server side or adding network latency can only decrease this.
</p>

<h4>Some results from LinkedIn</h4>

<p>
Here is the throughput we see from a single multithreaded client talking to a single server where the "hot" data set is in memory under artificially heavy load in our performance lab:
</p>

<pre>
	Reads: 19,384 req/sec
	Writes: 16,559 req/sec
</pre>
<p>
Note that this is to a single node cluster so the replication factor is 1. Obviously doubling the replication factor will halve the client req/sec since it is doing 2x the operations. So these numbers represent the maximum throughput from one client, by increasing the replication factor, decreasing the cache size, or increasing the data size on the node, we can make the performance arbitrarily slow. Note that in this test, the server is actually fairly lightly loaded since it has only one client so this does not measure the maximum throughput of a server, just the maximum throughput from a single client.
</p>
<p>
Here is the request-latency we see at LinkedIn on our production servers measured on the server side (e.g. from the time we get the request off the wire to the time we start writing back to the wire):
</p>

<pre>
Median GET: 0.015 ms
Median PUT: 0.040 ms
99.99 percentile GET: 0.227 ms
99.99 percentile PUT: 2.551 ms
</pre>

<p>
Note that this is very fast, and tells us that the bdb cache hit and/or pagecache hit ratio is extremely high for this particular dataset.
</p>

<p>
This does not include network or routing overhead. From the client-side we do not measure the individual voldemort requests in production, but instead time a full GET, processing with simple business logic, and PUT. For this we see avg. latencies of around 1-2ms for the complete modification including both read, update, and store operations.
</p>

<h4>Your Millage May Vary</h4>

If the numbers you see in your own tests do not look like what you expect please chime in on the mailing list. We have tuned and tested certain configurations and would like to gather data on other configurations and may be able to help with settings.

<?php require "../includes/footer.php" ?>