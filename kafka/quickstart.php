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

<h5>1. send() API </h5>

Using the producer is quite simple:

<pre>
String host = "localhost";
int port = 9092;
int bufferSize = 64*1024;
int connectionTimeoutMs = 30*1000;
int reconnectInterval = 1000;
SimpleProducer producer = new SimpleProducer(host, port, bufferSize, connectionTimeoutMs, reconnectInterval);

String topic = "test";
int partition = 0;
List<Message> messages = Arrays.asList(new Message("a message".getBytes()), 
                                       new Message("another message".getBytes()),
                                       new Message("a third message".getBytes()));
producer.send(topic, partition, messages)
</pre>

<h5>2. Log4j appender </h5>

Data can also be produced to a Kafka server in the form of a log4j appender. In this way, minimal code needs to be written in order to send some data across to the Kafka server. 
Here is an example of how to use the Kafka Log4j appender -

Start by defining the Kafka appender in your log4j.properties file.
<pre>
<small>// define the kafka log4j appender config parameters</small>
log4j.appender.KAFKA=kafka.log4j.KafkaAppender
<small>// set the hostname of the kafka server</small>
log4j.appender.KAFKA.Host=localhost
<small>// set the port on which the Kafka server is listening for connections</small>
log4j.appender.KAFKA.Port=9092
<small>// the topic under which the logger messages are to be posted</small>
log4j.appender.KAFKA.Topic=test-topic
<small>// the serializer to be used to turn an object into a Kafka message</small>
log4j.appender.KAFKA.Serializer=kafka.log4j.AppenderStringSerializer
<small>// do not set the above KAFKA appender as the root appender</small>
log4j.rootLogger=INFO
<small>// set the logger for your package to be the KAFKA appender</small>
log4j.logger.test.package=INFO, KAFKA
</pre>

Data can be sent using a log4j appender as follows -

<pre>
Logger logger = Logger.getLogger(classOf[KafkaLog4jAppenderTest])    
logger.info("test")
</pre>

<h5>3. Asynchronous batching producer </h5>
This is a higher level producer API, providing tunable batching of messages and asynchronous dispatch of batches of serialized messages to the configured Kafka server.

<p>The batching of data can be controlled using the following parameters -
<ul>
<li> queue size &ndash; this parameter specifies the total size of the in memory queue used by the batching producer. A batch cannot be larger than this value. </li>
<li> batch size &ndash; this parameter specifies a batch of data buffered in the producer queue. Once more data than this size is accumulated, it is serialized and sent to the Kafka server. </li>
<li> serializer class &ndash; this specifies the serializer to be used by the async producer to serialize the incoming data into sets of Kafka messages, before sending them to the Kafka server. </li>
<li> queue time in ms &ndash; this parameter controls the time for which the batched data lives in the queue. Once this time expires, the data in the queue is serialized and dispatched to the Kafka server. </li>
</ul>
</p>

Here is some code on how to use the asynchronous batching producer -

<pre>
Properties props = new Properties();
props.put("host", "localhost");
props.put("port", "9092");
props.put("queue.size", "200");
props.put("serializer.class", "kafka.producer.StringSerializer");
ProducerConfig config = new ProducerConfig(props);
    
SimpleProducer basicProducer =  new SimpleProducer(host, port, 64*1024, 100000, 10000);

AsyncKafkaProducer[String] producer = new AsyncKafkaProducer[String](config, basicProducer, new StringSerializer());

<small>// start the async producer</small>
producer.start();
for(i <- 0 until 200) {
   producer.send("test");
}
producer.close();
</pre>

Here is a simple string serializer used by the above example -

<pre>
class StringSerializer extends Serializer<String> {
  public String toEvent(Message message) { return message.toString(); }
  public Message toMessage(String event) { return new Message(event.getBytes); }
  public getTopic(String event) { return event.concat("-topic"); }
}
</pre>

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
ConsumerConnector consumerConnector = Consumer.create(consumerConfig);

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

