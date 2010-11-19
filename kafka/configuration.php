<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2> Configuration </h2>

<h3> Server </h3>

<h4> server.properties </h4>

<p>This file contains parameters required to start up a single instance of Kafka. Here is what each of those parameters mean -</p>

<li><i>hostname</i>&mdash; the hostname of the broker. If not set, will pick up from the value returned from getLocalHost</li></li>

<li><i>port</i>&mdash; the broker port to listen and accept connections on</li>

<li><i>broker.id</i>&mdash; the id for this server</li>

<li><i>socket.send.buffer</i>&mdash; the SO_SNDBUFF buffer of the socket sever sockets</li>

<li><i>socket.receive.buffer</i>&mdash; the SO_RCVBUFF buffer of the socket sever sockets</li>

<li><i>max.socket.request.bytes</i>&mdash; the maximum number of bytes in a socket request</li>

<li><i>num.threads</i>&mdash; the number of threads the server uses</li>

<li><i>monitoring.period.secs</i>&mdash; the interval in which to measure performance statistics</li>

<li><i>num.partitions</i>&mdash; the number of log partitions per topic</li> 

<li><i>log.dir</i>&mdash; the directory in which the log data is kept</li> 

<li><i>log.file.size</i>&mdash; the maximum size of a single log file</li>

<li><i>log.flush.interval</i>&mdash; the number of messages to accept without flushing the log to disk</li>

<li><i>log.retention.hours</i>&mdash; the number of hours to keep log data before deleting it</li>

<li><i>log.cleanup.interval.mins</i>&mdash; the number of minutes between checking for logs eligible for deletion</li>

<li><i>enable.zookeeper</i>&mdash; enable zookeeper registration in the server</li>

<li><i>topic.flush.intervals.ms</i>&mdash; topic flush interval map topic_name => key, int => flush interval in seconds</li>

<li><i>log.default.flush.interval.ms</i>&mdash; default topic flush interval in ms</li>

<li><i>log.default.flush.scheduler.interval.ms</i>&mdash; default topic scheduler flusher time interval to schedule flush on log files </li>

<li><i>topic.partition.count.map</i>&mdash; topic partition count map (topic_name => key, int => # of partitions)</li>

<h3> Zookeeper Consumer </h3>

<h4> consumer.properties </h4>

<li><i>groupid</i>&mdash; consumer group ID 

<li><i>consumerid</i>&mdash; Set this explicitly for only testing purpose. It is generated automatically if not set</li>

<li><i>zk.session.timeoutms</i>&mdash; the socket timeout for network requests</li>

<li><i>socket.buffersize</i>&mdash; the socket receive buffer for network requests</li>

<li><i>fetch.size</i>&mdash; the number of byes of messages to attempt to fetch</li>

<li><i>backoff.incrementms</i>&mdash; to avoid repeatedly polling a broker node which has no new data we will backoff every time we get an incomplete response from that broker (i.e. fewer than fetchSize bytes). Each successive incomplete request increments the backoff by this amount</li> 

<li><i>autocommit.enable</i>&mdash; if true automatically commit any message set fetched without waiting for confirmation from the consumer of processing</li>

<li><i>autocommit.intervalms</i>&mdash; the interval at which the auto commit thread will commit the data</li>

<li><i>queuedchunks.max</i>&mdash; max fetched chunks in a queue </li>

<li><i>autooffset.reset</i>&mdash; if the consumer tries fetching an invalid offset, this options decides if the offset can automatically be reset to either the earliest or the latest offset available on the server </li>  

<li><i>consumer.timeoutms</i>&mdash; this option times out the consumer threads if there is no data consumed for this period</li>

<li><i>embeddedconsumer.topics</i>&mdash; this option is useful for replicating the data from a remote Kafka server locally. The replication is performed for the topics specified here. The format is topic1:1,topic2:1</li> 

<?php require "../includes/footer.php" ?>

