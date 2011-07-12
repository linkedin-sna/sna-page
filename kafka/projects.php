<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>Current Work</h1>
	
<p>
  Below is a list of major projects we know people are currently pursuing. If you have thoughts on these or want to help, please <a href="http://groups.google.com/group/kafka-dev">let us know</a>.
</p>

<h3>Improved Stream Processing Libraries</h3>

<p>
We recently added the rich producer library that allows partitioned message production. This combined with the partition affinity of the consumers, gives the ability to do partitioned stream processing. One thing that is not very well developed is the patterns and libraries to support this. What we have in mind is a scala DSL to make it easy to group, aggregate, and otherwise transforms these infinite streams.
</p>

<h3>Replication</h3>

<p>
Messages are currently written to a single broker with no replication between brokers. We would like to provide replication between brokers and expose options to the producer to block until a configurable number of replicas have acknowledged the message to allow the client to control the fault-tolerance semantics.
</p>

<h3>Compression</h3>

<p>
We have a patch that provides end-to-end message set compression from producer to broker and broker to consumer with no need for intervening decompression. We hope to add this feature soon.
</p>

<h1>Project Ideas</h1>

<p>
Below is a list of projects which would be great to have but haven't yet been started. Ping the <a href="http://groups.google.com/group/kafka-dev">mailing list</a> if you are interested in working on any of these.
</p>

<h3>Clients In Other Languages</h3>

<p>
We offer a JVM-based client for production and consumption and also a rather primitive native python client. It would be great to improve this list. The lower-level protocols are well documented <a href="design.php">here</a> and should be relatively easy to implement in any language that supports standard socket I/O.
</p>

<h3>Convert Hadoop InputFormat or OutputFormat to Scala</h3>
<p>
We have an Hadoop InputFormat and OutputFormat that were contributed and are in use at LinkedIn. This code is in Java, though, which means it doesn't quite fit in well with the project. It would be good to convert this code to Scala to keep things consistent.
</p>

<h3>Long Poll</h3>

<p>
The consumer currently uses a simple polling mechanism. The fetch request always returns immediately, yielding no data if no new messages have arrived, and using a simple backoff mechanism when there are no new messages to avoid to frequent requests to the broker. This is efficient enough, but the lowest possible latency of the consumer is given by the polling frequency. It would be nice to enhance the consumer API to allow an option in the fetch request to have the server block for a given period of time waiting for data to be available rather than immediately returning and then waiting to poll again. This would provide somewhat improved latency in the low-throughput case where the consumer is often waiting for a message to arrive.
</p>

<h3>Syslogd Producer</h3>

<p>
We currently have a custom producer and also a log4j appender to work for "logging"-type applications. Outside the java world, however, the standard for logging is syslogd. It would be great to have an asynchronous producer that worked with syslogd to support these kinds of applications.
</p>

<h3>Hierarchical Topics</h3>

<p>
Currently streams are divided into only two levels&mdash;topics and partitions. This is unnecessarily limited. We should add support for hierarchical topics and allow subscribing to an arbitrary subset of paths. For example one could have /events/clicks and /events/logins and one could subscribe to either of these alone or get the merged stream by subscribing to the parent directory /events.
</p>

<p>
In this model, partitions are naturally just subtopics (for example /events/clicks/0 might be one partition). This reduces the conceptual weight of the system and adds some power.
</p>

<h3>Pluggable Offset Consumer Offset Storage Strategies</h3>

<p>
Currently consumer offsets are persisted in Zookeeper which works well for many use cases. There is no inherent reason the offsets need to be stored here, however. We should expose a pluggable interface to allow alternate storage mechanisms.
</p>

<h1>Recently Completed Projects</h1>

The following are some recently completed projects from this list.

<h3>Hadoop Consumer</h3>
<p>
Provide an InputFormat for Hadoop to allow running Map/Reduce jobs on top of Hadoop data.
</p>

<h3>Hadoop Producer</h3>
<p>
Provide an OutputFormat for Hadoop to allow Map/Reduce jobs to publish data to Kafka.
</p>

<h3>Console Consumer</h3>
<p>
The interaction with zookeeper and complexity of the elastic load balancing of consumers makes implementing the equivalent of the rich consumer interface outside of the JVM somewhat difficult (implementing the low-level fetch api is quite easy). A simple approach to this problem could work similar to Hadoop Streaming and simply provide a consumer which dumps to standard output in some user-controllable format. This can be piped to another program in any language which simply reads from standard input to receive the data from the stream.
</p>

<h3>Rich Producer Interface</h3>
<p>
The current producer connects to a single broker and publishes all data there. This feature would add a higher-level api would allow a cluster aware producer which would semantically map messages to kafka nodes and partitions. This allows partitioning the stream of messages with some semantic partition function based on some key in the message to spread them over broker machines&mdash;e.g. to ensure that all messages for a particular user go to a particular partition and hence appear in the same stream for the same consumer thread.
</p>

<h1>Project ideas for Scalathon</h1>

The following are some smaller features that you can hack on and play with Kafka -

<h3>Restful producer API</h3>
We need to make the Kafka server support RESTful producer requests. This allows Kafka to be used in any programming language without implementing the wire protocol in each language. It also makes it easier for web applications to produce data to Kafka. Please refer to the <a href="http://linkedin.jira.com/browse/KAFKA-71">JIRA</a> to contribute. 

<h3>Pluggable decoder for the consumer</h3>
Since 0.6, the <a href="http://sna-projects.com/kafka/javadoc/current/">producer</a> allows a user to plug in an Encoder that converts data of type T to a Kafka message. We need to do the same thing on the consumer side, by allowing the user to plug in a Decoder that converts a message into an object of type T. Please refer to the <a href="http://linkedin.jira.com/browse/KAFKA-70">JIRA</a> to contribute.

<h3>Producer ACK</h3>
Currently, the <a href="http://sna-projects.com/kafka/javadoc/current/">producer</a> does not wait for an acknowledgement (ACK) from the Kafka server. The producer just sends the data across and the server appends it to the appropriate log for a topic, but doesn't send an ACK back to the producer. Ideally, after handling the producer's request and writing the data to the log, the server should send back and ACK to the producer. And the producer should proceed sending the next request only after it receives the ACK from the server. Please refer to the <a href="http://linkedin.jira.com/browse/KAFKA-16">JIRA</a> to contribute.

<h3>Size based retention policy</h3>
The kafka server garbage collects logs according to a time-based retention policy (log.retention.hours). Ideally, the server should also support a size based retention policy (log.retention.size) to prevent any one topic from occupying too much disk space. Please refer to the <a href="http://linkedin.jira.com/browse/KAFKA-3">JIRA</a> to contribute.

<?php require "../includes/footer.php" ?>