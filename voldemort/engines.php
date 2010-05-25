<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>Storage Engines</h1>

Voldemort supports multiple pluggable storage engines which have different performance and durability trade-offs.

<h2>BDB JE</h2>

<p>
This is the storage engine most commonly used. It runs locally in the process and is extremely fast, especially when the data set is cacheable.
</p>

<p>
It uses a log-structured B-Tree which makes writes extremely fast. This means data is only appended to the end of the log file, with periodic cleanup taking place to compact data. More information is available on Oracle's site.
</p>

<p>
There are several settings we have found helpful for performance 
</p>

To configure this storage engine type set the following as the persistence type:

<pre>
	
</pre>

[settings]

<h2>Views</h2>

<h2>Read Only</h2>

The read only storage engine gives very high-efficiency access to read-only data. It supports the highest ratio of data-to-memory of any of the storage engines, and needs no internal cache (as it relies on the operating system page cache). Stores of this kind can be transparently "swapped" or rolled back to allow a bulk update of the entire data set.

The use of this storage engine for serving batch computed data is described in great detail here and here.

<h2>MySQL</h2>

This storage engine uses mysql. The mysql server needs to be running and presumably would be configured locally on each of the Voldemort machines.

The schema for the mysql is uniform with the serialization handled by Voldemort.

<h2>Memory</h2>

The memory storage engine is mostly used for testing.

<h2>Cache</h2>

This is a variation of the memory storage engine that flushes data when it fills up.

<?php require "../includes/footer.php" ?>