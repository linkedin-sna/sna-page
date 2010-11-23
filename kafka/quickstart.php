<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Quick Start</h3>
	
<h3> Step 1: Download the code </h3>

Download a recent stable release from here.

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
<b>&gt; bin/kafka-server.sh config/server.properties</b>
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

<h3>Step 4: Write some code</h3>

Below is some very simple examples of using Kafka for sending messages, more complete examples can be found in the Kafka source code in the examples/ directory.

<h4>Producer Code</h4>

Using the producer is quite simple:

<pre>
String host = "localhost";
int port = 9092;
int bufferSize = 64*1024;
int connectionTimeoutMs = 30*1000;
int reconnectInterval = 1000;
KafkaProducer producer = new KafkaProducer(host, port, bufferSize, connectionTimeoutMs, reconnectInterval);

String topic = "test";
int partition = 0;
List<Message> messages = Arrays.asList(new Message("a message".getBytes()), 
	                                   new Message("another message".getBytes()),
	                                   new Message("a third message".getBytes()));
producer.send(topic, partition, messages)
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
for(KafkaMessageStream>> stream: streams) {
  final KafkaMessageStream stream = topicStream.getValue();
  executors.submit(new Runnable() {
    public void run() {
      for(Message message: stream) {
        // process message
      }	
    }
  });
}
</pre>

<h4> Simple Consumer </h4>

Kafka has a lower-level consumer api for reading message chunks directly from servers. Under most circumstances this should not be needed. It's usage is as follows:

<pre>
<small>// create a consumer to connect to the server host, port, socket timeout of 10 secs, socket receive buffer of ~1MB</small>
SimpleConsumer consumer = new SimpleConsumer(host, port, 10000, 1024000);

long offset = 0;
while (true) {
  <small>// create a fetch request for topic “test”, partition 0, current offset, and fetch size of 1MB</small>
  FetchRequest fetchRequest = new FetchRequest("test", 0, offset, 1000000);

  <small>// get the message set from the consumer and print them out</small>
  ByteBufferMessageSet messageSets = consumer.fetch(fetchRequest);
  for(message : messages) {
    System.out.println("consumed: " + Utils.toString(message.payload, "UTF-8"))
    <small>// advance the offset after consuming each message</small>
    offset += MessageSet.entrySize(message)
  }
}
</pre>

<?php require "../includes/footer.php" ?>
