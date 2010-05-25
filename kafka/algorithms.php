<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Algorithms</h3>
	
This page describes the basic algorithms used by kafka for clustering and log management.

<h3>Log Management</h3>

Write me.

<h3>Clustering</h3>

<h4>Zookeeper Directories</h4>

The following zookeeper directories are used to control cluster behavior.

<h5>Notation</h5>

When an element in a path is denoted <code>[xyz]</code>,
that means that the value of xyz is not fixed and there is in fact a znode for each possible value of <code>xyz</code>. 
For example <code>/topics/[topic]</code> would be a directory named /topics containing a directory 
for each topic name. Numerical ranges are also given such as <code>[0...5]</code> to indicate the subdirectories 0, 1, 2, 3, 4. An arrow <code>--></code> is
used to indicate the contents of a znode. For example <code>/hello --> world</code> would indicate a znode <code>/hello</code> containing the value "world".

<h5>Partition Registery</h5>

<pre>/topics/[topic]/[node_id-partition_num]</pre>

A list of topics and sub-partitions under the topic. Each node registers its partitions under this registry. This directory is used for discovering new nodes with relevant partitions. When new partitions appear under a topic, the subscribers rebalance themselves to consume the new partitions.

<h5>Node Registry</h5>

<pre>
/nodes/[0...N] --> host:port
</pre>

<p>
This is a list of all present nodes. Node id is configured on each server.  The server registers itself on start-up, storing the host and port in the znode.
This is purely informational&mdash;consumers only rebalance when they see new partitions they care about, not just because there is a new node.
</p>

<p>
An attempt to register a node id that is already in use (say because two servers are configured with the same node id) is an error.
</p>

<h5>Consumers and Consumer Groups</h5>

<p>
Consumers of topics also register themselves in Zookeeper, in order to balance the consumption of data and track their offsets in each partition.
</p>
<p>
It is assumed that consumption is spread over multiple machines. To indicate which group of consumers are acting in concert as
a single logical consumer, each consumer is given a group_id. For example if one consumer is your hadoop etl process, which is run in many mapper processes then you might assign these consumers the id <code>hadoop-etl</code> so that each event in a given topic goes to exactly one consumer in the consumer group.	
</p>

<p>
The consumers in a group divide up the partitions as fairly as possible so that each partition will be consumed by exactly one consumer in each consumer group.	
</p>

<h5>Consumer Id Registry</h5>
<p>
In addition to the group_id which is shared by all consumers in a group, each consumer is given a transient, unique consumer_id for identification purposes.
Consumer ids are registered in the following directory:
</p>

<pre>
/consumers/[group_id]/[topic]/ids/[0...C] -> host:port
</pre>

<p>
Each of the C consumers in the group 
register under the group and topics they consume, giving their current host and port. This consumer id is a transient sequential znode, so it will change if the consumer reconnects.
</p>

<h5>Consumer Offset Tracking</h5>

Consumers track the maximum offset they have consumed in each partition. This value is stored in a zookeeper directory:

<pre>
/consumers/[group_id]/[topic]/offsets/[node_id-partition_id] --> offset_counter_value
</pre>

<h5>Partition owner registry</h5>

<p>
Each partition has a single owner within a given consumer group. The consumer must establish its ownership of a given partition before any consumption can begin. To establish its ownership it writes its own id to a file named by the (node, partition) pair:
</p>

/consumers/[group_id]/[topic]/owner/[node_id-partition_id] --> consumer_node_id

<h4>Server Co-ordination</h4>

The servers are basically independent, so they only publish information about what they have. When a node joins it registers itself under the node registry directory and registers its available. When it starts the server registers its node id under the node registery directory, and registers its partitions for each topic it has. As topics are added on each server their partitions are registered as well.

<h4>Consumer Co-ordination</h4>
<p>
The clients need to react to the addition of removal of both servers and other consumers in their group.
</p>
<h5>Consumer Registration</h5>
<p>
When it begins consuming a given topic, the client does the following:
</p>
<ol>
  <li>Register a watch on the consumer id registry for the topic to be alerted of any new consumers joining or any consumers leaving the cluster.
  <li>Register a watch on the node/partition registry for the topic to be alerted of any new servers joining or leaving the cluster
  <li>Register itself in the consumer registry for its group and receive its consumer id. This registration should trigger a rebalancing on all clients.
</ol>

<h5>Consumer Rebalancing</h5>
<p>
We need to elect a single consumer for each partition. In addition, to balance load, we would prefer to have the same number of partitions being consumed by each consumer. Each consumer executes the following procedure to claim its partitions whenever a rebalancing is triggered:
</p>
<ol>
  <li>Fetch all the consumer ids for the given topic, and compute C, the number of consumers.
  <li>Fetch all the partitions for the given topic and sort them lexicographically.
  <li>If the current node has node_id = n then it will choose every partition with index i if (i mod C) == n as its own.
  <li>For each partition claimed by the above formula attempt to register as the owner
  <li>Since each node is acting asynchronously, this will need to be re-attempted if registration fails, until all obsolete registrations have been discarded.
</ol>

<?php require "../includes/footer.php" ?>