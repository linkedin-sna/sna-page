<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>Current Work</h1>
	
<p>
  Below is a list of major projects we are currently pursuing. If you have thoughts on these or want to help, please <a href="http://groups.google.com/group/kafka-dev">let us know</a>.
</p>

<h3>Rich Producer Interface</h3>

<p>
The current producer connects to a single broker and publishes all data there. This feature would add a higher-level api would allow a cluster aware producer which would semantically map messages to kafka nodes and partitions. This allows partitioning the stream of messages with some semantic partition function based on some key in the message to spread them over broker machines&mdash;e.g. to ensure that all messages for a particular user go to a particular partition and hence appear in the same stream for the same consumer thread.
</p>

<h3>Map/Reduce Support</h3>

<p>
Streams of messages are the natural building block for higher-level processing built by stringing a set of topics together with intermediate processing between them. This is unlikely to be exactly map/reduce as it appears in Hadoop, but we are currently working on providing the ability to semantically repartition streams of data to allow the equivalent of a "reducer". This provides capabilities along the lines of stream processing systems or vaguely a sort of "online map/reduce". This is closely related to the rich producer interface described above.
</p>

<p>
Technically Hadoop map/reduce provides a number of facilities, namely
</p>
<ul>
  <li>Raw storage (HDFS)</li>
  <li>Job and task management facilities which physically ship code to slaves for execution</li>
  <li>Basic map/reduce APIs and support code</li>
</ul>

<p>
In Kafka the stream of messages is the natural analogue to files in HDFS. We do intend to provide interfaces for processing and partitioning similar to map/reduce, but we are currently not working on anything equivalent to the task management facilities of Hadoop. This is less important since consumers dynamically register themselves and claim a portion of the stream, and record their consumption progress. Because of this a naive task management solution need only copy the "task" code to each machine and start it which can easily be done with rsync and ssh.
</p>

<h3>Replication</h3>

<p>
Messages are currently written to a single broker with no replication between brokers. We would like to provide replication between brokers and expose options to the producer to block until a configurable number of replicas have acknowledged the message to allow the client to control the fault-tolerance semantics.
</p>

<h3>Compression</h3>

<p>
We have a patch that provides end-to-end message set compression from producer to broker and broker to consumer with no need for intervening decompression. We hope to add this feature soon.
</p>

<h3>Stdout Consumer</h3>

<p>
The interaction with zookeeper and complexity of the elastic load balancing of consumers makes implementing the equivalent of the rich consumer interface outside of the JVM somewhat difficult (implementing the low-level fetch api is quite easy). A simple approach to this problem could work similar to Hadoop Streaming and simply provide a consumer which dumps to standard output in some user-controllable format. This can be piped to another program in any language which simply reads from standard input to receive the data from the stream.
</p>

<h3>Hadoop Consumer</h3>

<p>
We are currently working on refactoring the existing Hadoop consumer (which can be found under contrib/hadoop-consumer) to serve as an InputFormat, which seems to us a cleaner way to provide this functionality.	
</p>

<h1>Project Ideas</h1>

<p>
Not all the projects are started yet. Below is a list of projects which would be great to have but haven't yet been started. Ping the <a href="http://groups.google.com/group/kafka-dev">mailing list</a> if you are interested in working on any of these.
</p>

<h3>Other Languages</h3>

We offer a JVM-based client for production and consumption and also a rather primitive native python client. It would be great to improve this list. The lower-level protocols are well documented <a href="design.php">here</a> and should be relatively easy to implement in any language that supports standard socket I/O.

<h3>Long Poll</h3>

The consumer currently uses a simple polling mechanism. The fetch request always returns immediately, yielding no data if no new messages have arrived, and using a simple backoff mechanism when there are no new messages to avoid to frequent requests to the broker. This is efficient enough, but the lowest possible latency of the consumer is given by the polling frequency. It would be nice to enhance the consumer API to allow an option in the fetch request to have the server block for a given period of time waiting for data to be available rather than immediately returning and then waiting to poll again. This would provide somewhat improved latency in the low-throughput case where the consumer is often waiting for a message to arrive.

<?php require "../includes/footer.php" ?>