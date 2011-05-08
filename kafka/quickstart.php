<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Quick Start</h3>
	
<h3> Step 1: Download the code </h3>

<a href="downloads" title="Kafka downloads">Download</a> a recent stable release.

<pre>
<b>&gt; tar xzf kafka-&lt;VERSION&gt;.tgz</b>
<b>&gt; cd kafka-&lt;VERSION&gt;</b>
</pre>

<h3>Step 2: Start the server</h3>

Kafka brokers and consumers use this for co-ordination. 
<p>
First start the zookeeper server. You can use the convenience script packaged with kafka to get a quick-and-dirty single-node zookeeper instance.

<pre>
<b>&gt; bin/zookeeper-server-start.sh config/zookeeper.properties</b>
[2010-11-21 23:45:02,335] INFO Reading configuration from: config/zookeeper.properties 
...
</pre>

Now start the Kafka server:
<pre>
<b>&gt; bin/kafka-server-start.sh config/server.properties</b>
jkreps-mn-2:kafka-trunk jkreps$ bin/kafka-server-start.sh config/server.properties 
[2010-11-21 23:51:39,608] INFO starting log cleaner every 60000 ms (kafka.log.LogManager)
[2010-11-21 23:51:39,628] INFO connecting to ZK: localhost:2181 (kafka.server.KafkaZooKeeper)
...
</pre>

<h3>Step 3: Send some messages</h3>

A toy producer script is available to send plain text messages. To use it, run the following command:

<pre>
<b>&gt; bin/kafka-producer-shell.sh --server kafka://localhost:9092 --topic test</b>
> hello
sent: hello (14 bytes)
> world
sent: world (14 bytes)
</pre>

<h3>Step 5: Start a consumer</h3>

Start a toy consumer to dump out the messages you sent to the console:

<pre>
<b>&gt; bin/kafka-consumer-shell.sh --topic test --props config/consumer.properties</b>
Starting consumer...
...
consumed: hello
consumed: world
</pre>

If you have each of the above commands running in a different terminal then you should now be able to type messages into the producer terminal and see them appear in the consumer terminal.

<h3>Step 6: Write some code</h3>

Below is some very simple examples of using Kafka for sending messages, more complete examples can be found in the Kafka source code in the examples/ directory.

<h4>Producer Code</h4>

<h5>2. Log4j appender </h5>

Data can also be produced to a Kafka server in the form of a log4j appender. In this way, minimal code needs to be written in order to send some data across to the Kafka server. 
Here is an example of how to use the Kafka Log4j appender -

Start by defining the Kafka appender in your log4j.properties file.
<pre>
<small>// define the kafka log4j appender config parameters</small>
log4j.appender.KAFKA=kafka.producer.KafkaLog4jAppender
<small>// <b>REQUIRED</b>: set the hostname of the kafka server</small>
log4j.appender.KAFKA.Host=localhost
<small>// <b>REQUIRED</b>: set the port on which the Kafka server is listening for connections</small>
log4j.appender.KAFKA.Port=9092
<small>// <b>REQUIRED</b>: the topic under which the logger messages are to be posted</small>
log4j.appender.KAFKA.Topic=test-topic
<small>// the serializer to be used to turn an object into a Kafka message. Defaults to kafka.producer.DefaultStringEncoder</small>
log4j.appender.KAFKA.Serializer=kafka.test.AppenderStringSerializer
<small>// do not set the above KAFKA appender as the root appender</small>
log4j.rootLogger=INFO
<small>// set the logger for your package to be the KAFKA appender</small>
log4j.logger.test.package=INFO, KAFKA
</pre>

Data can be sent using a log4j appender as follows -

<pre>
Logger logger = Logger.getLogger(classOf[KafkaLog4jAppender])    
logger.info("test")
</pre>

<h5>1. Producer API </h5>

With release 0.6, we introduced a new producer API - <code>kafka.producer.Producer&lt;T&gt;</code>. Here are examples of using the producer -

<ol>
<li>First, start a local instance of the zookeeper server
<pre>./bin/zookeeper-server-start.sh config/zookeeper.properties</pre>
</li>
<li>Next, start a kafka broker
<pre>./bin/kafka-server-start.sh config/server.properties</pre>
</li>
<li>Now, create the producer with all configuration defaults and using zookeeper based broker discovery.
<pre>
Properties props = new Properties();
props.put(“zk.connect”, “127.0.0.1:2181”);
props.put("serializer.class", "kafka.serializer.StringEncoder");
ProducerConfig config = new ProducerConfig(props);
Producer&lt;String, String&gt; producer = new Producer&lt;String, String&gt;(config);
</pre>
</li>
<li>Send a single message
<pre>
ProducerData&lt;String, String&gt; data = new ProducerData&lt;String, String&gt;("test-topic", "test-message");
producer.send(data);	
</pre>
</li>
<li>Send multiple messages to multiple topics in one request
<pre>
List&lt;String&gt; messages = new java.util.ArrayList&lt;String&gt;();
messages.add("test-message1");
messages.add("test-message2");
ProducerData&lt;String, String&gt; data1 = new ProducerData&lt;String, String&gt;("test-topic1", messages);
ProducerData&lt;String, String&gt; data2 = new ProducerData&lt;String, String&gt;("test-topic2", messages);
List&lt;ProducerData&lt;String, String&gt;&gt; dataForMultipleTopics = new ArrayList&lt;ProducerData&lt;String, String&gt;&gt;();
dataForMultipleTopics.add(data1);
dataForMultipleTopics.add(data2);
producer.send(dataForMultipleTopics);	
</pre>
</li>
<li>Partition on a key
<pre>
ProducerData&lt;String, String&gt; data = new ProducerData&lt;String, String&gt;("test-topic", "test-key", "test-message");
producer.send(data);
</pre>
</li>
<li>Partition using custom partitioner
<p>If you are using zookeeper based broker discovery, <code>kafka.producer.Producer&lt;T&gt;</code> can route your data to a particular broker partition based on a <code>kafka.producer.Partitioner&lt;T&gt;</code>, specified through the <code>partitioner.class</code> config parameter. It defaults to <code>kafka.producer.DefaultPartitioner</code>. If not, then it sends each request to a random broker partition.</p>
<pre>
class TrackingDataPartitioner extends Partitioner[MemberIdLocation] {
  def partition(data: MemberIdLocation, numPartitions: Int): Int = {
    (data.location.hashCode % numPartitions)
  }
}
<small>// create the producer config to plug in the above partitioner</small>
Properties props = new Properties();
props.put(“zk.connect”, “127.0.0.1:2181”);
props.put("partitioner.class", "xyz.MemberIdPartitioner");
ProducerConfig config = new ProducerConfig(props);
Producer&lt;String, String&gt; producer = new Producer&lt;String, String&gt;(config);
</pre>
</li>
<li>Use custom Encoder 
<p>The producer takes in a required config parameter <code>serializer.class</code> that specifies an <code>Encoder&lt;T&gt;</code> to convert T to a Kafka Message. Default is the no-op kafka.serializer.DefaultEncoder.</p>
<pre>
class TrackingDataSerializer extends Encoder&lt;TrackingData&gt; {
  <small>// Say you want to use your own custom Avro encoding</small>
  CustomAvroEncoder avroEncoder = new CustomAvroEncoder();
  def toMessage(event: TrackingData):Message = {
	new Message(avroEncoder.getBytes(event));
  }
}
</pre>
</li>
<li>Using static list of brokers, instead of zookeeper based broker discovery
<p>Some applications would rather not depend on zookeeper. In that case, the config parameter <code>broker.list</code> 
can be used to specify the list of all brokers in the Kafka cluster.- the list of all brokers in your Kafka cluster in the following format - 
<code>broker_id1:host1:port1, broker_id2:host2:port2...</code></p>
<pre>
<small>// you can stop the zookeeper instance as it is no longer required</small>
./bin/zookeeper-server-stop.sh	
<small>// create the producer config object </small>
Properties props = new Properties();
props.put(“broker.list”, “0:localhost:9092”);
props.put("serializer.class", "kafka.serializer.StringEncoder");
ProducerConfig config = new ProducerConfig(props);
<small>// send a message using default partitioner </small>
Producer&lt;String, String&gt; producer = new Producer&lt;String, String&gt;(config);	
List&lt;String&gt; messages = new java.util.ArrayList&lt;String&gt;();
messages.add("test-message");
ProducerData&lt;String, String&gt; data = new ProducerData&lt;String, String&gt;("test-topic", messages);
producer.send(data);	
</pre>
</li>
<li>Finally, the producer should be closed, through
<pre>producer.close();</pre>
</li>
</ol>

<h4>Consumer Code</h4>

The consumer code is slightly more complex as it enables multithreaded consumption:

<pre>
// specify some consumer properties
Properties props = new Properties();
props.put("zk.connect", "localhost:2181");
props.put("zk.connectiontimeout.ms", "1000000");
props.put("groupid", "test_group");

// Create the connection to the cluster
ConsumerConfig consumerConfig = new ConsumerConfig(props);
ConsumerConnector consumerConnector = Consumer.createJavaConsumerConnector(consumerConfig);

// create 4 partitions of the stream for topic “test”, to allow 4 threads to consume
Map&lt;String, List&lt;KafkaMessageStream&gt;&gt; topicMessageStreams = 
    consumerConnector.createMessageStreams(ImmutableMap.of("test", 4));
List&lt;KafkaMessageStream&gt; streams = topicMessageStreams.get("test")

// create list of 4 threads to consume from each of the partitions 
ExecutorService executor = Executors.newFixedThreadPool(4);

// consume the messages in the threads
for(final KafkaMessageStream>> stream: streams) {
  executors.submit(new Runnable() {
    public void run() {
      for(Message message: stream) {
        // process message
      }	
    }
  });
}
</pre>

<h4>Hadoop Consumer</h4>

<p>
Providing a horizontally scalable solution for aggregating and loading data into Hadoop was one of our basic use cases. To support this use case, we provide a Hadoop-based consumer which spawns off many map tasks to pull data from the Kafka cluster in parallel. This provides extremely fast pull-based Hadoop data load capabilities (we were able to fully saturate the network with only a handful of Kafka servers).
</p>

<p>
Usage information on the hadoop consumer can be found <a href="https://github.com/kafka-dev/kafka/tree/master/contrib/hadoop-consumer">here</a>.
</p>

<h4>Simple Consumer</h4>

Kafka has a lower-level consumer api for reading message chunks directly from servers. Under most circumstances this should not be needed. But just in case, it's usage is as follows:

<pre>
<small>// create a consumer to connect to the server host, port, socket timeout of 10 secs, socket receive buffer of ~1MB</small>
SimpleConsumer consumer = new SimpleConsumer(host, port, 10000, 1024000);

long offset = 0;
while (true) {
  <small>// create a fetch request for topic “test”, partition 0, current offset, and fetch size of 1MB</small>
  FetchRequest fetchRequest = new FetchRequest("test", 0, offset, 1000000);

  <small>// get the message set from the consumer and print them out</small>
  ByteBufferMessageSet messages = consumer.fetch(fetchRequest);
  for(message : messages) {
    System.out.println("consumed: " + Utils.toString(message.payload, "UTF-8"))
    <small>// advance the offset after consuming each message</small>
    offset += MessageSet.entrySize(message);
  }
}
</pre>

<?php require "../includes/footer.php" ?>

