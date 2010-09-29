<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Consumer Design</h3>
	
<h3>API<h3>
	
The Kafka consumer API abstracts away the individual requests to the Kafka servers and provides a higher level API which provides iterators over the infinite stream of messages. Here is an example of using the consumer to consume data:

<code>
val consumer = new Consumer(new ConsumerConfig(...))

// begin consumption of two topics
val topic1: MessageStream = consumer.consume("my_topic_1")
val topic2: MessageStream = consumer.consume("my_topic_2")

// process messages from topic1
for(message <- topic1) {
	// process the message
}

// record the processing of all messages
topic1.commit()

// close topic1
topic1.close()

// close all topics
consumer.close()
</code>

<h3>Internals</h3>

The consumer is divided into two components:
1. The fetcher - fetches data on the active partitions
2. The cluster connector - manages connection to zookeeper for cluster management and consumer balancing

<h3>ConsumerFetcher</h3>

<p>
The consumer fetcher is a background thread that holds the connections to the kafka servers and fetches the data for consumption. It is responsible for throttling its requests to the servers.
</p>
<p>
Fetched data is put into a queue of unconsumed data for consumption by one of the ConsumerIterators.
</p>
<p>
The ConsumerFetcher is responsible for registration with zookeeper and for balancing its consumption with other consumers.
</p>
<p>
The ConsumerFetcher maintains a PartitionRepository which stores an entry for each topic/partition entry as well as the current consumed offset as a PartitionOffset object. The fetcher thread adds FetchedDataChunk objects which contain the PartitionOffset as well as the fetched MessageSet.
</p>
<p>
The ConsumerFetcher exposes a commit(topic: String) call, which will write all PartitionOffset data back to zookeeper.	
</p>

<h3>KafkaMessageStream</h3>
	
This represents a holder for the iterator for a particular topic. It also exposes the commit() call for that topic. This
commit call uses the same lock the iterator uses for next() and hasNext() to ensure only one of these executes at a given time.

<h3>ConsumerIterator</h3>

Each consumer has a single iterator for its messages which is feed by the fetcher. Internally the iterator
has a blocking queue of FetchedDataChunks. It reads off of its current chunk until it is exhausted, then it gets a new chunk. Each call to next() updates the PartitionOffset value.

<h3>Example Code</h3>
<pre>
  val fetcher = new ConsumerFetcher(new ConsumerConfig(...))
  fetcher.connect()
  val stream: KafkaMessageStream = fetcher.consume("my_topic")
  for(m <- stream) {
    process(m)	
  }
  stream.commit()
</pre>
	
<?php require "../includes/footer.php" ?>