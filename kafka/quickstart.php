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

Using the sync producer is quite simple:

<pre>
Properties props = new Properties();
props.put("host", "localhost");
props.put("port", "9092");
props.put("buffer.size", String.valueOf(64*1024));
props.put("connect.timeout.ms", String.valueOf(30*1000));
props.put("reconnect.interval", "1000");

SyncProducer producer = new SyncProducer(new SyncProducerConfig(props));

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

<h5>3. New producer API </h5>

With release 0.6, we introduced a new producer API - <code>kafka.producer.Producer&lt;T&gt;</code>. The producer takes in a required config parameter <code>serializer.class</code> that specifies an <code>Encoder&lt;T&gt;</code> to convert T to a Kafka Message. Also, one of the following config parameters need to be specified -

<ul>
<li>zk.connect - the zookeeper connection URL, if you want to turn on automatic broker discovery</li>
<li>broker.partition.info - the list of all brokers in your Kafka cluster in the following format - broker_id1:host1:port1, broker_id2:host2:port2...</li>
</ul>

<pre>
Properties props = new Properties();
props.put(“serializer.class”, “kafka.test.TestEncoder”);
props.put(“zk.connect”, “127.0.0.1:2181”);
ProducerConfig config = new ProducerConfig(props);
Producer<String> producer = new Producer<String>(config);

class TestEncoder extends Encoder<String> {
  public Message toMessage(String event) { return new Message(event.getBytes); }
}
</pre>

<p>If you are using zookeeper based broker discovery, <code>kafka.producer.Producer&lt;T&gt;</code> can route your data to a particular broker partition based on a <code>kafka.producer.Partitioner&lt;T&gt;</code>, specified through the <code>partitioner.class</code> config parameter. It defaults to <code>kafka.producer.DefaultPartitioner</code>. If not, then it sends each request to a random broker partition.</p>

The send API takes in the data to be sent through a <code>kafka.producer.ProducerData&lt;K, T&gt;</code> object, where K is the type of the key used by the <code>Partitioner&lt;T&gt;</code> and T is the type of data to send to the broker. In this example, the key and value type, both are String.

<p>You can batch multiple messages and pass a <code>java.util.List&lt;T&gt;</code> as the last argument to the <code>kafka.producer.ProducerData&lt;K, T&gt;</code> object.</p>

<pre>
List<String> messages = new java.util.ArrayList<String>
messages.add("test1")
messages.add(“test2”)
producer.send(new ProducerData<String, String>(“test_topic”, "test_key", messages))
</pre>

<p>You can also route the data to a random broker partition, by not specifying the key in the <code>kafka.producer.ProducerData&lt;K, T&gt;</code> object. The key defaults to <code>null</code>.</p>

<pre>
producer.send(new ProducerData<String, String>(“test_topic”, messages))
</pre>

<p>Finally, the producer should be closed, through</p>

<pre>producer.close();</pre>

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

