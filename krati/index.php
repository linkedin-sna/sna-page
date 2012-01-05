<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Krati
</h1>

<p>
<strong>Krati</strong> is a simple persistent data store with very low latency and high throughput.
It is designed for easy integration with read-write-intensive applications with little effort
in tuning configuration, performance and JVM garbage collection.
This software is published under the terms of the Apache Software License version 2.0,
a copy of which has been included in the LICENSE file shipped with the Krati distribution.
</p>

<p>
Simply put, Krati
  <ul>
    <li>supports varying-length data array</li>
    <li>supports key-value data store access</li>
    <li>performs append-only writes in batches</li>
    <li>has write-ahead redo logs and periodic checkpointing</li>
    <li>has automatic data compaction (i.e. garbage collection)</li>
    <li>is memory-resident (or OS page cache resident) yet persistent</li>
    <li>allows single-writer and multiple readers</li>
  </ul>
</p>

<p>
Or you can think of Krati as
  <ul>
    <li>Berkeley DB JE backed by hash-based indexing rather than B-tree</li>
    <li>A hashtable with disk persistency at the granularity of update batch</li>
  </ul>
</p>

<p>
Krati is not a relational database management system (RDBMS).
Krati relies on hash-based indexing instead of tree-structured indexing
such as B-tree and B+ tree indexes for achieving high throughput and low latency. 
This design decision makes Krati suitable for random reads and writes but not for range scans. 
Krati supports batch-based persistency via periodic checkpointing and write-ahead redo logs,
but not undos or rollbacks. It does not offer any transactional guarantee besides batch-based persistency.
</p>

<h3>
Krati @ LinkedIn
</h3>

<ul>
  <li><strong>Search Content Store</strong></li>
  <p>
  The Krati project was initiated for serving Linked people search content from main memory.
  Search content data, which originates from a variety of databases, are collected and stored
  in multiple Krati data nodes. Each data node is handling a partition of 5,000,000 LinkedIn members.
  Broker service is provided to serve reads from data nodes. Each data node needs to be first bootstrapped
  from various database sources and then put online to serve realtime traffic: reads from the broker and
  writes from databases.
  </p>
  <p>
  Due to the persistency of Krati, each node is able to recover from crash nicely without bootstrapping again
  from various database sources. Upon restart or recovery, each node only needs to catch up with the latest
  updates from databases. Replicas can be added to ensure service availablity.
  </p>

  <li><strong>Voldemort Storage Engine</strong></li>
  <p>
  Krati has also been integrated into <a href="http://sna-projects.com/voldemort/">Voldemort</a> as a storage engine
  to provide a remote, distributed, and partition-based storage solution. The low latency and high throughput of
  Krati makes it a good fit with Voldemort readonly stores.
  </p>
</ul>

<h3>
Origin of Krati
</h3>

<p>
<strong>Krati</strong> is a time measurement in Sanskrit and stands for 68,000th of one second.
It provides a quantification for low latency and thus serves as an inspiring motivation with respect to
the performance goals of Krati as a fast data store.
</p>

<?php require "../includes/footer.php" ?>
