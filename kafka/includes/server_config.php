<table class="data-table">
<tr>
        <th>name</th>
        <th>default</th>
        <th>description</th>
</tr>
<tr>
    <td><code>brokerid</code></td>
    <td>none</td>
    <td>Each broker is uniquely identified by an id. This id serves as the brokers "name", and allows the broker to be moved to a different host/port without confusing consumers.</td>
</tr>
<tr>
     <td><code>log.flush.interval</code></td>
     <td>500</td>
     <td>Controls the number of messages accumulated in each topic (partition) before the data is flushed to disk and made available to consumers.</td>  
</tr>
<tr>
    <td><code>log.default.flush.scheduler.interval.ms</code></td>
    <td>3000</td>
    <td>Controls the interval at which logs are checked to see if they need to be flushed to disk.</td>
</tr>
<tr>
    <td><code>log.default.flush.interval.ms</code> </td>
    <td>log.default.flush.scheduler.interval.ms</td>
    <td>Controls the maximum time that a message in any topic is kept in memory before flushed to disk. The value only makes sense if it's a multiple of <code>log.default.flush.scheduler.interval
.ms</code></td>
</tr>
<tr>
    <td><code>topic.flush.intervals.ms</code></td>
    <td>none</td>
    <td>Per-topic overrides for <code>log.default.flush.interval.ms</code>. Controls the maximum time that a message in selected topics is kept in memory before flushed to disk. The per-topic value only makes sense if it's a multiple of <code>log.default.flush.scheduler.interval.ms</code>. E.g., topic1:1000,topic2:2000</td>
</tr>
<tr>
    <td><code>log.cleanup.interval.mins</code></td>
    <td>10</td>
    <td>Controls how often the log cleaner checks logs eligible for deletion. A log file is eligible for deletion if it hasn't been modified for <code>log.retention.hours</code> hours.</td>
</tr>
<tr>
    <td><code>log.dir</code></td>
    <td>none</td>
    <td>Specifies the root directory in which all log data is kept.</td>
</tr>
<tr>
    <td><code>log.file.size</code></td>
    <td>1*1024*1024*1024</td>
    <td>Controls the maximum size of a single log file.</td>
</tr>
<tr>
    <td><code>num.threads</code></td>
    <td>Runtime.getRuntime().availableProcessors</td>
    <td>Controls the number of worker threads in the broker to serve requests.</td>
</tr>
<tr>
    <td><code>num.partitions</code> </td>
    <td>1</td>
    <td>Specifies the default number of partitions per topic.</td>
</tr>
<tr>
    <td><code>topic.partition.count.map</code></td>
    <td>none</td>
    <td>Override parameter to control the number of partitions for selected topics. E.g., topic1:10,topic2:20</td>
</tr>
<tr>
    <td><code>zk.connect</code> </td>
    <td>localhost:2182/kafka</td>
    <td>Specifies the zookeeper connection string in the form hostname:port/chroot. Here the chroot is a base directory which is prepended to all path operations (this effectively namespaces all kafka znodes to allow sharing with other applications on the same zookeeper cluster)</td>
</tr>
<tr>
    <td><code>zk.connectiontimeout.ms</code> </td>
    <td>6000</td>
    <td>Specifies the max time that the client waits to establish a connection to zookeeper.</td>
</tr>
<tr>
    <td><code>zk.sessiontimeout.ms</code> </td>
    <td>6000</td>
    <td>The zookeeper session timeout.</td>
</tr>
<tr>
    <td><code>zk.synctime.ms</code></td>
    <td>2000</td>
    <td>Max time for how far a ZK follower can be behind a ZK leader</td>
</tr>
</table>
