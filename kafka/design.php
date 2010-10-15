<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Why we built this</h2>

<p>
Kafka is a messaging system that was built at LinkedIn to serve as the foundation for our activity stream processing.
</p>

<p>
Activity stream data is a normal part of any website for reporting on usage of the site. Activity data is things like page views, information about what content was shown, searches, etc. This kind of thing is usually handled by logging the activity out to some kind of file and then periodically aggregating these files for analysis.
</p>

<p>
In recent years, however, activity data has become a critical part of the production features of websites, and a slightly more sophisticated set of infrastructure is needed.

<h2>Use cases for activity stream data</h2>
<ul>
	<li>"News feed" features that broadcast the activity of your friends.</li>
	<li>Relevance and ranking uses which may want to count ratings or votes or click-through to determine which of a given set of items is most popular or relevant.</li>
	<li>Security: Sites need to block abusive crawlers, rate-limiting apis, detecting spamming attempts, and other detection and prevention systems that key off site activity.</li>
	<li>Operational monitoring: Most sites needs some kind of real-time, heads-up monitoring that can track performance and trigger alerts if something goes wrong.</li>
	<li>Reporting and Batch processing: It is common to load data into a datawarehouse or Hadoop system for offline analysis and reporting on business activity</li>
</ul>
</p>
<h2>Characteristics of activity stream data</h2>	
<p>
This high-throughput stream of immutable activity data represents a real computational challenge as the volume may easily be 10x or 100x larger than the next largest data source on a site.
</p>
<p>
Traditional log file aggregation is a respectable and scalable approach to supporting offline use cases like reporting or batch processing; but is too high latency for real-time processing and tends to have rather high operational complexity. Likewise, our experience was that the real-time and near-real-time use-cases are poorly served by existing messaging and queuing systems; these systems seem to handle large unconsumed queues very poorly, and treat persistence as an after thought to support a lagging consumer rather than the common case for offline systems like Hadoop that may only consume some sources once per hour or day. It is desirable to have a single pipeline that can support all these use cases.
</p>
<p>
Kafka supports fairly general messaging semantics, so nothing ties it to activity processing, however that was our motivating use case.
</p>

<h2>Deployment</h2>

<p>
The following diagram gives a simplified view of the deployment topology at LinkedIn.
</p>

<img src="">

<p>
Note that a single kafka cluster handles all activity data from all different sources. This provides a single pipeline of data for both online and offline consumers. This tier acts as a buffer between live activity and asynchronous processing. We also use a datacenter-local replica to support our offline processing systems which live in their own datacenters.
</p>

<h1>Major Design Elements</h1>

<p>
There is a small number of major design decisions that make Kafka different from most other messaging systems:
<ol>
<li>Kafka is designed for persistent messages as the common case</li>
<li>Throughput rather than features are the primary design constraint</li>
<li>State about <i>what</i> has been consumed is maintained as part of the consumer not the server</li>
<li>Kafka is explicitly distributed. It is assumed that producers, brokers, and consumers are all spread over multiple machines.</li>
</ol>
</p>

<p>
Each of these decisions will be discussed in more detail below.
</p>

<h2>Basics</h2>
<p>
First some basic terminology and concepts.
</p>
<p>
<i>Messages</i> are the fundamental unit of communication. Messages are <i>published</i> to a <i>topic</i> by a <i>producer</i> which means they are physically sent to a <i>broker</i> (probably on another machine). Some number of <i>consumers</i> subscribe to a topic, and each published message is delivered to all the consumers.
</p>
<p>
Producers, consumers, and brokers can all be distributed over multiple machines, and multiple processes within each machine. In the case of consumers, each consumer process belongs to a <i>consumer group</i> and each message is delivered to exactly one process within every consumer group. Hence a consumer group allows many processes and machines to logically act as a single consumer. This creates a generalized framework of which the traditional <i>queue</i> and <i>topic</i> found in JMS are a special case (the former is the case where all consumers share a single consumer group, the later the case where all have different consumer groups).
</p>

<h2>Message Persistence and Caching</h2>

<h3>Don't fear the filesystem!</h3>
<p>
Kafka relies heavily on the filesystem for storing and caching messages. There is a general perception that "disks are slow" which makes people skeptical that a persistent structure can offer competitive performance. In fact disks are both much slower and must faster than people expect depending on how they are used; and a properly designed disk structure can be faster than the network.
</p>

<p>
The key fact about disk performance is that the throughput of hard drives has been diverging from the latency of a disk seek for the last decade. As a result the performance of linear writes on a 6 7200rpm SATA RAID-5 array is about 300MB/sec but the performance of random writes is only about 50k/sec&mdash;a difference of nearly 10000X. These linear reads and writes are the most predictable of all usage patterns, and hence the one detected and optimized best by the operating system using read-ahead and write-behind techniques that prefetch data in large block multiples and group smaller logical writes into large physical writes. A further discussion of this issue can be found in this <a href="http://deliveryimages.acm.org/10.1145/1570000/1563874/jacobs3.jpg">ACM Queue article</a>; they actually find that sequential disk access can be faster than random memory access!
</p>
<p>
To compensate for this performance divergence modern operating systems have become increasingly aggressive in their use of main memory for disk caching. Any modern OS will happily divert <i>all</i> free memory to disk caching with little performance penalty when the memory is reclaimed. All disk reads and writes will go through this unified cache. This feature cannot easily be turned off without using direct I/O, so even if a process maintains an in-process cache of the data, this data will likely be duplicated in OS pagecache, effectively storing everything twice.
</p>
<p>
Furthermore anyone who has spent any time with Java memory usage knows two things:
<ol>
	<li>The memory overhead of objects is very high, often doubling the size of the data stored (or worse).</li>
	<li>Java garbage collection becomes increasingly sketchy and expensive as the in-heap data increases.</li>
</ol>
</p>
<p>
As a results of these factors using the filesystem and relying on pagecache is superior to maintaining an in-memory cache or other structure&mdash;we at least double the available cache by having automatic access to all free memory, and likely double again by storing a compact byte structure rather than individual objects. Doing so will result in a cache of up to 28-30GB on a 32GB machine without GC penalties. Furthermore this cache will stay warm even if the service is restarted, whereas the in-process cache will need to be rebuilt in memory (which for a 10GB cache may take 10 minutes) or else it will need to start with a completely cold cache (which likely means terrible initial performance). It also greatly simplifies the code as all logic for maintaining coherency between the cache and filesystem is now in the OS, which tends to do so more efficiently and more correctly then one-off in-process attempts. If your disk usage favors linear reads then read-ahead is effectively pre-populating this cache with useful data on each disk read.
</p>
<p>
This suggests a design which is very simple: rather than maintain as much as possible in-memory and flush to the filesystem only when necessary, we invert that. All data is immediately written to a persistent log on the filesystem without any call to flush the data. In effect this just means that it is transferred into the kernel's pagecache where the OS can flush it later. Then we add a configuration driven flush policy to allow the user of the system to control how often data is flushed to the physical disk (every N messages) to put a bound on the amount of data "at risk" in the event of a hard crash.
</p>
<p>
This style of pagecache-centric design is described in an <a href="http://varnish.projects.linpro.no/wiki/ArchitectNotes">article</a> on the design of Varnish here (along with a healthy helping of arrogance).
</p>	
<h3>Constant Time Suffices</h3>
<p>
The persistent data structure used in messaging systems metadata is often a BTree. Btrees are the most versatile data structure available, and make it possible to support a wide variety of transactional and non-transactional semantics in the messaging system. They do come with a fairly high cost, though: Btree operations are O(log N). Normally O(log N) is considered essentially equivalent to constant time, but this is not true for disk operations. Disk seeks come at 10 ms a pop, and each disk can do only one seek at a time so parallelism is limited. Hence even a handful of disk seeks leads to very high overhead. Since storage systems mix very fast cached operations with actual physical disk operations, the observed performance of tree structures is often superlinear. Furthermore btrees require a very sophisticated page or row locking implementation to avoid locking the entire tree on each operation. The implementation must pay a fairly high price for row-locking or else effectively serialize all reads. Because of the heavy reliance on disk seeks it is not possible to effectively take advantage of the improvements in drive density, and one is forced to use small (&lt; 100GB) high RPM SAS drives to maintain a sane ratio of data to seek capacity.
</p>
<p>
Intuitively a persistent queue could be built on simple reads and appends to files as is commonly the case with logging solutions. Though this structure would not support the rich semantics of a btree implementation, but it has the advantage that all operations are O(1) and reads do not block writes or each other. This has obvious performance advantages since the performance is completely decoupled from the data size--one server can now take full advantage of a number of cheap, low-rotational speed 1+TB SATA drives. Though they have poor seek performance, these drives often have comparable performance for large reads and writes at 1/3 the price and 3x the capacity.
</p>
<p>
Having access to virtually unlimited disk space without penalty means that we can provide some features not usually found in a messaging system. For example, typically messaging systems differentiate between queues, which retain data until it is consumed by a single consumer, and topics, which immediately deliver messages to all subscribers.
</p>
<h2>Maximizing Efficiency</h2>

<p>
Our assumption is that the volume of messages is extremely high, indeed it is some multiple of the total number of page views for the site (since a page view is one of the activities we processes). Furthermore we assume each message published is read at least once (and often multiple times), hence we optimize for consumption rather than production.
</p>
<p>
There are two common causes of inefficiency: two many network requests, and excessive byte copying.	
</p>
<p>
To encourage efficiency, the APIs are built around a "message set" abstraction that naturally groups messages. This allows network requests to group messages together and amortize the overhead of the network roundtrip rather than sending a single message at a time.
</p>
<p>The <code>MessageSet</code> implementation is itself a very thin API that wraps a byte array or file. Hence there is no separate serialization or deserialization step required for message processing, message fields are lazily deserialized as needed (or not deserialized if not needed).
</p>
<p>
The message log maintained by the broker is itself just a directory of message sets that have been written to disk. This abstraction allows a single byte format to be shared by both the broker and the consumer (and to some degree the producer, though producer messages are checksumed and validated before being added to the log).
</p>
<p>
Maintaining this common format allows optimization of the most important operation: network transfer of persistent log chunks. Modern unix operating systems offer a highly optimized code path for transferring data out of pagecache to a socket; in Linux this is done with the sendfile system call. Java provides access to this system call with the <code>FileChannel.transferTo</code> api. 
</p>
<p>
To understand the impact of sendfile, it is important to understand the common data path for transfer of data from file to socket:
<ol>
	<li>The operating system reads data from the disk into a buffer for the application to consume</li>
	<li>The application reads the data from kernel space into a user-space buffer</li>
	<li>The application writes the data back into kernel space into a socket buffer</li>
	<li>The operating system copies the data from the socket buffer to the NIC buffer where it is sent over the network</li>
</ol>
</p>
<p>
This is clearly inefficient, there are four copies, two system calls. Using sendfile, this re-copying is avoided by simply pointing the OS at the offset in pagecache to copy from and using this directly as the buffer for the network transfer. So in this optimized path, only the final copy to the NIC buffer is needed.
</p>
<p>
The fact that multiple consumers of a topic is a common case means this optimization can make the use of a disk-based structure actually be more efficient than an in-memory structure&mdash;the in-memory structure would be recopied into kernel space every time the message was consumed, whereas in the zero-copy approach it is copied in only once and re-used on each consumption. By optimizing the common case message consumption performance can be processed at the limit of the network connection.
</p>
<p>
For more background on the sendfile and zero-copy support in Java, see this <a href="http://www.ibm.com/developerworks/linux/library/j-zerocopy">article</a> on IBM developerworks.	
</p>

<h2>Consumer state</h2>

<p>
Keeping track of <i>what</i> has been consumed is one of the key things a messaging system must provide. It is not intuitive, but recording this state is one of the key performance points for the system. The reason for this is that state about what has been consumed is a persistant update of a mutable and potentially random access quanity. Hence it is likely to be bound by the seek time of the storage system not the write bandwidth (as described above).
</p>
<p>
Most messaging systems keep metadata about what messages have been consumed on the broker. That is, as a message is handed out to a consumer, the broker records that fact locally. This is a fairly intuitive choice, and indeed for a single machine server it is not clear where else it could go. Since the data structure used for storage in many messaging systems scale poorly, this is also pragmatic choice--since the broker knows what is consumed it can immediately delele it, keeping the data size small.
</p>
<p>
What is perhaps not obvious, is that getting the broker and consumer to come into agreement about what has been consumed is not a trivial problem. If the broker records a message as <b>consumed</b> immediately every time it is handed out over the network then if the consumer fails to process the message (say because it crashes or the request times out or whatever) then that message will be lost. To solve this problem, many messaging system add an acknowledgement feature which means that messages are only marked as <b>sent</b> not <b>consumed</b> when they are sent; the broker waits for a specific acknowledgement from the consumer to record the message as <b>consumed</b>. This strategy fixes the problem of losing messages, but creates new problems. First of all, if the consumer processes the message but fails before it can send an acknowledgement then the message will be consumed twice. The second problem is around performance, now the broker must keep multiple states about every single message (first to lock it so it is not given out a second time, and then to mark it as permanently consumed so that it can be removed). Tricky problems must be dealt with, like what to do with messages that are sent but never acknowledged.
</p>
<h3>Message delivery semantics</h3>
<p>
So clearly there are multiple possible message delivery guarantees that could be provided:
<ul>
  <li>
	<i>At most once</i>&mdash;this handles the first case described. Messages are immediately marked as consumed, so they can't be given out twice, but many failure scenarios may lead to losing messages.
  </li>
  <li>
	<i>At least once</i>&mdash;this is the second case where we guarantee each message will be delivered at least once, but in failure cases may be delivered twice.
  </li>
  <li>
	<i>Exactly once</i>&mdash;this is what people actually want, each message is delivered once and only once.
  </li>
</ul>	
</p>
<p>
This problem is heavily studied, and is a variation of the "transaction commit" problem. Algorithms that provide exactly once semantics exist, two- or three-phase commits and Paxos variants being examples, but they come with some drawbacks. They typically require multiple round trips and may have poor guarantees of liveness (they can halt indefinitely). The FLP result provides some of the fundamental limitations on these algorithms.
</p>
<p>
Kafka's does two unusual things with respect to metadata. First the stream is partitioned on the brokers into a set of distinct partitions. The semantic meaning of these partitions is left up to the producer and the partition for a message set is specified with the message set when it is produced. Within a partition messages are stored in the order in which they arrive at the broker, and will be given out to consumers in that same order. This means that rather than store metadata about each message (marking it as consumed, say) all that needs to be stored is the "high water mark" for each combination of consumer, topic, and partition. Hence the total metadata required to summarize the state of the consumer is actually quite small. In Kafka we refer to this high-water mark as "the offset" for reasons that will become clear in the implementation section.
</p>
<p>
Kafka also moves this state about what has been consumed to the client. This provides an easy out for some simple cases, and has a few side benefits. In the simplest cases the consumer may simply be entering some aggregate value into a centralized, transactional OLTP database. In this case the consumer can store the state of what is consumed in the same transaction as the database modification. This solves a distributed consensus problem, by removing the distributed part! A similar trick works for some non-transactional systems as well. A search system can store its consumer state with its index segments. Though it may provide no durability guarantees, this means that the index is always in sync with the consumer state: if an unflushed segment is lost in a crash, the indexing starts from the last viable segment, effectively going back in time, but doing so in a consistent fashion. Likewise our Hadoop load job which does parallel loads from Kafka, does a similar trick--individual mappers handle data partitions. Though a partition's mapper may fail, another mapper will simply restart from the same position the previous failed mapper started from.
</p>
<p>
There are two side benefits of this decision. The first is that the consumer can deliberately <i>rewind</i> back to an old offset and reconsume data. This violates the common contract of a queue, but turns out to be an essential feature for many offline data consumers. Offline systems like Hadoop or a data warehouse often proceed in a state machine processing style where batches of data are passed from job to job, with each job processing its input and producing new output. This allows batch processing to be effectively moved back in time be rerunning certain processing steps and removing intermediate data. This is a very common remediation when a problem is discovered in a data processing step, or when a new processing job is being tested. The ability to reload makes Kafka fit very nicely in a batch processing workflow system.
</p>
<h2>Distribution</h2>

<h1>Implementation Details</h1>

<p>
The following gives a brief description of some relevant implementation details.
</p>
<h2>API Design</h2>
<p>
	
</p>

<h2>Network Layer</h2>
<p>
The network layer is a fairly straight-forward NIO server, and will not be described in great detail. The sendfile implementation is done by giving the <code>MessageSet</code> interface a <code>writeTo</code> method. This allows the file-backed message set to use the more efficient <code>transferTo</code> implementation instead of a in-process buffered write. The threading model is a single acceptor thread and <i>N</i> processor threads which handle a fixed number of connections each. This design has been pretty thoroughly tested <a href="http://sna-projects.com/blog/2009/08/introducing-the-nio-socketserver-implementation">elsewhere</a> and found to be simple to implement and fast. The protocol is kept quite simple to allow for future the implementation of clients in other languages.
</p>
<h2>Messages</h2>
<p>
Messages consist of a fixed-size header and variable length opaque byte array payload. The header contains a format version and a CRC32 checksum to detect corruption or truncation. Leaving the payload opaque is the right decision: there is a great deal of progress being made on serialization libraries right now, and any particular choice is unlikely to be right for all uses. Needless to say a particular application using Kafka would likely mandate a particular serialization type as part of its usage. The <code>MessageSet</code> interface is simply an iterator over messages with specialized methods for bulk reading and writing to an NIO <code>Channel</code>.
</p>
<h2>Log</h2>
<p>
A log for a topic named "my_topic" with two partitions consists of two directories (namely <code>my_topic_0</code> and <code>my_topic_1</code>) populated with data files containing the messages for that topic. The format of the log files is a sequence of "log entries""; each log entry is a 4 byte integer <i>N</i> storing the message length which is followed by the <i>N</i> message bytes. Each message is uniquely identified by a 64-bit integer <i>offset</i> giving the byte position of the start of this message in the stream of all messages ever sent to that topic on that server. Each log file is named with the offset of the first message it contains. So the first file created will be 00000000000.kafka, and each additional file will have an integer name roughly <i>N</i> bytes from the previous file where <i>N</i> is the max log file size given in the configuration.
</p>
<p>
The use of the message offset as the message id is unusual. Our original idea was to use a GUID generated by the producer, and maintain a mapping from GUID to offset on each broker. But since a consumer must maintain an ID for each server, the global uniqueness of the GUID provides no value. Furthermore the complexity of maintaining the mapping from a random id to an offset requires a heavy weight index structure which must be synchronized with disk, essentially requiring a full persistent random-access data structure. Thus to simplify the lookup structure we decided to use a simple per-partition atomic counter which could be coupled with the partition id and node id to uniquely identify a message; this makes the lookup structure simpler, though multiple seeks per consumer request are likely still necessary. However once we settled on a counter, the jump to directly using the offset seemed natural&mdash;both after all are monotonically increasing integers unique to a partition. Since the offset is hidden from the consumer API this decision is ultimately an implementation detail and we went with the more efficient approach.
</p>
<img src="images/kafka_log.png">
<h3>Writes</h3>
<p>
The log allows serial appends which always go the last file. This file is rolled over to a fresh file when it reaches a configurable size (say 1GB). The log takes a configuration parameter <i>M</i> which gives the number of messages to write before forcing the OS to flush the file to disk. This gives a durability guarantee of losing at most <i>M</i> messages in the event of a system crash.
</p>
<h3>Reads</h3>
<p>
Reads are done by giving the 64-bit logical offset of a message and an <i>S</i>-byte max chunk size. This will return an iterator over the messages contained in the <i>S</i>-byte buffer. <i>S</i> is intended to be larger than any single message, but in the event of an abnormally large message, the read can be retried multiple times, each time doubling the buffer size, until the message is read successfully. A maximum message and buffer size can be specified to make the server reject messages larger than some size, and to give a bound to the client on the maximum it need ever read to get a complete message. It is likely that the read buffer ends with a partial message, this is easily detected by the size delimiting.
</p>
<p>
The actual process of reading from an offset requires first locating the log segment file in which the data is stored, calculating the file-specific offset from the global offset value, and then reading from that file offset. The search is done as a simple binary search variation against an in-memory range maintained for each file.
</p>
<p>
The log provides the capability of getting the most recently written message to allow clients to start subscribing as of "right now". This is also useful in the case the consumer fails to consume its data within its SLA-specified number of days. In this case when the client attempts to consume a non-existant offset it is given an SLA and can either reset itself or fail as appropriate to the use case.
</p>
<h3>Deletes</h3>
<p>
Data is deleted one log segment at a time. The log manager allows pluggable delete policies to choose which files are eligible for deletion. The current policy deletes any log with a modification time of more than <i>N</i> days ago, though a policy which retained the last <i>N</i> GB could also be useful. To avoid locking reads while still allowing deletes that modify the segment list we use a copy-on-write style segment list implementation that provides consistent views to allow a binary search to proceed on an immutable static snapshot view of the log segments while deletes are progressing.
</p>
<h3>Guarantees</h3>
<p>
The log provides a configuration parameter <i>M</i> which controls the maximum number of messages that are written before forcing a flush to disk. On startup a log recovery process is run that iterates over all messages in the newest log segment and verifies that each message entry is valid. A message entry is valid if the sum of its size and offset are less than the length of the file AND the CRC32 of the message payload matches the CRC stored with the message. In the event corruption is detected the log is truncated to the last valid offset.
</p>
<p>
Note that two kinds of corruption must be handled: truncation in which an unwritten block is lost due to a crash, and corruption in which a nonsense block is ADDED to the file. The reason for this is that in general the OS makes no guarantee of the write order between the file inode and the actual block data so in addition to losing written data the file can gain nonsense data if the inode is updated with a new size but a crash occurs before the block containing that data is not written. The CRC detects this corner case, and prevents it from corrupting the log (though the unwritten messages are, of course, lost).
</p>

<h2>Distribution</h2>

<?php require "../includes/footer.php" ?>