<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2> Configuration </h2>

<h3> Important configuration properties for Kafka broker: </h3>

<p>
<ul>
<li> When deploying multiple broker servers in the same kafka instance, each broker needs to have a unique <code>brokerid</code>.
</li>

<li> For better performance, kafka server flushes data to disks periodically. A produced message is only exposed to the consumer after
it's flushed to disk. The flush frequency can be controlled by the number of messages received.
<ul>
<li>    <code>log.flush.interval</code> controls the number of messages accumulated in each topic (partition) before the data is flushed to disk.
</li>
</ul>
Alternatively, the flush frequency can be controlled by time.
<ul>
<li> <code>log.default.flush.scheduler.interval.ms</code> controls the frequency that the time-based log flusher checks whether any log needs to be flushed to disk.
</li>
<li> <code>log.default.flush.interval.ms</code> controls the maximum time that a message in any topic is kept in memory before flushed to disk. The value only makes sense if it's a multiple of <code>log.default.flush.scheduler.interval.ms</code>.
</li>
<li> <code>topic.flush.intervals.ms</code> controls the maximum time that a message in selected topics is kept in memory before flushed to disk. The per-topic value only makes sense if it's a multiple of <code>log.default.flush.scheduler.interval.ms</code>.
</li>
</ul>

<li><code>log.cleanup.interval.mins</code> controls how often the log cleaner checks logs eligible for deletion. A log file is eligible for deletion if it hasn't been modified for <code>log.retention.hours</code> hours.
</li>

<li><code>log.dir</code> specifies the root directory in which all log data is kept.</li> 

<li><code>log.file.size</code> controls the maximum size of a single log file.</li>

<li><code>num.threads</code> controls the number of worker threads in the broker to serve all requests.</li>

<li><code>num.partitions</code> specified the default number of partitions per topic.</li> 

<li><code>topic.partition.count.map</code> controls the number of partitions for selected topics. </li>

<li> <code>zk.connect</code> specifies the zookeeper connection string. </li>

<li> <code>zk.connectiontimeout.ms</code> specifies the max time that the client waits to establish a connection to zookeeper.</li>

<li> <code>zk.sessiontimeout.ms</code> is the zookeeper session timeout. </li>

<li> More details about server configuration can be found in the scala class <code>kafka.server.KafkaConfig</code>. </li>

</ul>
</p>


<h3> Important configuration properties for the high-level consumer: </h3>

<ul>
<li><code>groupid</code> is a string that uniquely identifies a set of consumers within the same consumer group. </li>

<li><code>autocommit.enable</code>, if set to true, the consumer periodically commits to zookeeper the latest consumed offset of each partition. </li>

<li><code>autocommit.interval.ms</code> is the frequency that the consumed offsets are committed to zookeeper. </li>

<li><code>autooffset.reset</code> controls what to do if an offset is out of range.
<ul>
 <li> <code>smallest</code>: automatically reset the offset to the smallest offset available on the broker.</li>
 <li> <code>largest</code> : automatically reset the offset to the largest offset available on the broker.</li>
 <li> <code>anything else</code>: throw an exception to the consumer.</li>
</ul>
</li>

<li><code>consumer.timeout.ms</code>: By default, this value is -1 and a consumer blocks indefinitely if no new message is available for consumption. By setting the value to a positive integer, a timeout exception is thrown to the consumer if no message is available for consumption after the specified timeout value. </li>

<li><code>zk.connect</code>, <code>zk.connectiontimeout.ms</code> and <code>zk.connectiontimeout.ms</code> are the same as described in the broker configuration.</li>

<li> More details about server configuration can be found in the scala class <code>kafka.consumer.ConsumerConfig</code>. </li>
</ul>
<?php require "../includes/footer.php" ?>

