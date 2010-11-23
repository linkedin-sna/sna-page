<table class="data-table">
<tr>
        <th>name</th>
        <th>default</th>
        <th>description</th>
</tr>
<tr>
    <td><code>brokerid</code></td>
    <td>none</td>
    <td>When deploying multiple broker servers in the same kafka instance, each broker needs to have a unique</td>
</tr>
<tr>
     <td><code>log.flush.interval</code></td>
     <td>1</td>
     <td>controls the number of messages accumulated in each topic (partition) before the data is flushed to disk.</td>  
</tr>
<tr>
    <td><code>log.default.flush.scheduler.interval.ms</code></td>
    <td>5000</td>
    <td>controls the frequency that the time-based log flusher checks whether any log needs to be flushed to disk.</td>
</tr>
<tr>
    <td><code>log.default.flush.interval.ms</code> </td>
    <td>log.default.flush.scheduler.interval.ms</td>
    <td>controls the maximum time that a message in any topic is kept in memory before flushed to disk. The value only makes sense if it's a multiple of <code>log.default.flush.scheduler.interval
.ms</code></td>
</tr>
<tr>
    <td><code>topic.flush.intervals.ms</code> </td>
    <td>none</td>
    <td>controls the maximum time that a message in selected topics is kept in memory before flushed to disk. The per-topic value only makes sense if it's a multiple of <code>log.default.flush.schedul
er.interval.ms</code>.</td>
</tr>
<tr>
    <td><code>log.cleanup.interval.mins</code></td>
    <td>10</td>
    <td>controls how often the log cleaner checks logs eligible for deletion. A log file is eligible for deletion if it hasn't been modified for <code>log.retention.hours</code> hours.</td>
</tr>
<tr>
    <td><code>log.dir</code></td>
    <td>none</td>
    <td>specifies the root directory in which all log data is kept.</td>
</tr>
<tr>
    <td><code>log.file.size</code></td>
    <td>1*1024*1024*1024</td>
    <td>controls the maximum size of a single log file.</td>
</tr>
<tr>
    <td><code>num.threads</code></td>
    <td>Runtime.getRuntime().availableProcessors</td>
    <td>controls the number of worker threads in the broker to serve all requests.</td>
</tr>
<tr>
    <td><code>num.partitions</code> </td>
    <td>1</td>
    <td>specified the default number of partitions per topic.</td>
</tr>
<tr>
    <td><code>topic.partition.count.map</code></td>
    <td>none</td>
    <td>controls the number of partitions for selected topics.</td>
</tr>
<tr>
    <td><code>zk.connect</code> </td>
    <td>localhost:2182</td>
    <td>specifies the zookeeper connection string. </td>
</tr>
<tr>
    <td><code>zk.connectiontimeout.ms</code> </td>
    <td>6000</td>
    <td>specifies the max time that the client waits to establish a connection to zookeeper.</td>
</tr>
<tr>
    <td><code>zk.sessiontimeout.ms</code> </td>
    <td>6000</td>
    <td>is the zookeeper session timeout. </td>
</tr>
<tr>
    <td><code>zk.synctime.ms</code></td>
    <td>2000</td>
    <td>max time for how far a ZK follower can be behind a ZK leader</td>
</tr>
</table>
