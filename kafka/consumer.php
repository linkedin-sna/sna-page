<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Consumer Design</h3>
	
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