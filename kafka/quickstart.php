<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Quick Start</h3>
	
<h3> Step 1: Download the code </h3>

Download a recent stable release from here

<h3> Step 2: Start a local Kafka server </h3>

>./kafka-server.sh server.properties

<h3> Step 3: Start a producer </h3>

Start a producer to send messages to the local kafka server for topic “test”

./run-class.sh kafka.TestProducer 
USAGE: kafka.TestProducer$ kafka.properties topic

>./run-class.sh kafka.TestProducer server.properties test
> hello
sent: hello (14 bytes)
> world
sent: world (14 bytes)

<h3> Step 4: Start a consumer </h3>

Start a consumer to process messages from the local Kafka server for topic “test” and default partition 0

./run-class.sh kafka.TestConsumer 
USAGE: kafka.TestConsumer$ kafka.properties topic partition

./run-class.sh kafka.TestConsumer server.properties test 0
Starting consumer...
multi fetched 28 bytes from offset 0
consumed: hello
consumed: world

<h2> Details </h2>

<h3> Server </h3>

<h4> 1. Start from the command line </h4>

You must first build the jar using ant, then do the following -

$ KAFKA_HOME=’/path/to/kafka’
$ cd $KAFKA_HOME
$ ./kafka-server.sh server.properties
[2010-11-18 10:33:00,608] INFO No log directory found, creating '/tmp/kafka-logs' (kafka.log.LogManager)
[2010-11-18 10:33:00,632] INFO starting log cleaner every 60000 ms (kafka.log.LogManager)
[2010-11-18 10:33:00,751] INFO Starting Kafka server... (kafka.server.KafkaServer)
[2010-11-18 10:33:00,833] INFO Awaiting connections on port 9092 (kafka.network.Acceptor)
[2010-11-18 10:33:00,839] INFO starting log flusher every 5000 ms with flush map Map() (kafka.log.LogManager)
[2010-11-18 10:33:00,839] INFO Server started. (kafka.server.KafkaServer)

<h4> Embedded server </h4>	

The server can be instantiated directly in your code.

val props = Utils.loadProps(“/path/to/server.properties”)
val server = new KafkaServer(new KafkaConfig(props))
server.startup

<h3> Consumer </h3>

<h4> Simple Consumer </h4>

// create a consumer to connect to the server host, port, socket timeout of 10 secs, socket receive buffer of ~1MB
val consumer = new SimpleConsumer(host, port, 10000, 1024000)

// create a fetch request for topic “test”, partition 0, offset 0 and fetch size of 1MB
val fetchRequest = new FetchRequest(“test”, 0, 0, 1000000)

// get the message set from the consumer
val messageSets = consumer.multifetch(fetchRequest)

// Iterate through the messages
for(message <- messages) {
   println("consumed: " + Utils.toString(message.payload, "UTF-8"))
}

<h4> Zookeeper Consumer </h4>

// create consumer properties
val props = new Properties
// specify the zookeeper connection url for the local zookeeper
props.put(“zk.connect”, “localhost:2181”)
// specify the zookeeper connection timeout
props.put(“zk.connectiontimeoutms”, “1000000”)
// specify the name of the consumer group
props.put(“groupid”, “test_group”)

// create the consumer config
val consumerConfig = new ConsumerConfig(props)

// create the zookeeper consumer 
val consumerConnector: ConsumerConnector = Consumer.create(consumerConfig)

// create 4 consumer partitioned streams for topic “test”
val topicMessageStreams = consumerConnector.createMessageStreams(Predef.Map(“test” -> 4))

// create list of 4 threads to consume from its respective message stream
var threadList = List[ZKConsumerThread]()
for ((topic, streamList) <- topicMessageStreams)
  for (stream <- streamList)
    threadList ::= new ZKConsumerThread(stream)

for (thread <- threadList)
  thread.start

// the class describing each consumer processing thread
class ZKConsumerThread(stream: KafkaMessageStream) extends Thread {
  val shutdownLatch = new CountDownLatch(1)

  override def run() {
    println("Starting consumer thread..")
    for (message <- stream) {
      println("consumed: " + Utils.toString(message.payload, "UTF-8"))
    }
    shutdownLatch.countDown
    println("thread shutdown !" )
  }

  def shutdown() {
    shutdownLatch.await
  }          
}

<?php require "../includes/footer.php" ?>
