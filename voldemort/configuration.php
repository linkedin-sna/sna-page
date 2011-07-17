<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Configuration</h2>

<p>There are three configuration files that control server operation:</p>
<p>
<ul>
<li>
<i>cluster.xml</i> &ndash; This holds the information about all the nodes (i.e. servers) in the cluster, what hostname they are at, the ports they use, etc. It is exactly the same for all voldemort nodes. It does not hold tuning parameters or data directories for those nodes, since that is not information public to the cluster but is specific to that particular nodes configuration.
</li>
<li>
	<i>stores.xml</i> &ndash; This holds the information about all the stores (i.e. tables) in the cluster. This includes information about the required number of successful reads to maintain consistency, the required number of writes, as well as how keys and values are serialized into bytes. It is the same on all nodes in the cluster.</li>
<li><i>server.properties</i> &ndash; This contains the tuning parameters that control a particular node. This includes the id of the local node so it knows which entry in cluster.xml corresponds to itself, also the threadpool size, as well as any configuration needed for the local persistence engine such as BDB or mysql. This file is different on each node.
</li>
</ul>
</p>

<p>Finally there is an environment variable, VOLDEMORT_HOME, that controls the directory in which data and configuration reside. You can see an example of how the configuration is layed out in the config/ subdirectory of the project. This includes sample configurations that you can modify with your own specifics.</p>

<h3>Cluster configuration</h3>

<p>Here is an example cluster.xml for a 2-node cluster with 8 data partitions. We also have optional 'zone' fields which allow you to map nodes to certain logical clusters ( datacenter, rack, etc ) called zones:

<pre>	
  &lt;cluster&gt;
    &lt!-- The name is just to help users identify this cluster from the gui --&gt
    &lt;name&gt;mycluster&lt;/name&gt;
    &lt;zone&gt;
      &lt;zone-id&gt;0&lt;/zone-id&gt;
      &lt;proximity-list&gt;1&lt;/proximity-list&gt;
    &lt;zone&gt;
    &lt;zone&gt;
      &lt;zone-id&gt;1&lt;/zone-id&gt;
      &lt;proximity-list&gt;0&lt;/proximity-list&gt;
    &lt;zone&gt;
    &lt;server&gt;
      &lt!-- The node id is a unique, sequential id beginning with 0 that identifies each server in the cluster--&gt
      &lt;id&gt;0&lt;/id&gt;
      &lt;host&gt;vldmt1.prod.linkedin.com&lt;/host&gt;
      &lt;http-port&gt;8081&lt;/http-port&gt;
      &lt;socket-port&gt;6666&lt;/socket-port&gt;
      &lt;admin-port&gt;6667&lt;/admin-port&gt;
      &lt;!-- A list of data partitions assigned to this server --&gt;
      &lt;partitions&gt;0,1,2,3&lt;/partitions&gt;
      &lt;zone-id&gt;0&lt;/zone-id&gt;
    &lt;/server&gt;
    &lt;server&gt;
      &lt;id&gt;1&lt;/id&gt;
      &lt;host&gt;vldmt2.prod.linkedin.com&lt;/host&gt;
      &lt;http-port&gt;8081&lt;/http-port&gt;
      &lt;socket-port&gt;6666&lt;/socket-port&gt;
      &lt;admin-port&gt;6667&lt;/admin-port&gt;
      &lt;partitions&gt;4,5,6,7&lt;/partitions&gt;
      &lt;zone-id&gt;1&lt;/zone-id&gt;
    &lt;/server&gt;
  &lt;/cluster&gt;
</pre>

<p>
One thing that is important to understand is that partitions are not static partitions of servers, but rather they are a mechanism for partitioning the key space in such a way that each key is statically mapped to a particular data
partition. What this means is that a particular cluster may support many stores each with different replication factors&mdash;the replication factor is not hardcoded in the cluster design. This is important, since some data is more important than other data, and the correct trade-off between performance and consistency for one store may be different from another store. 
</p>
<p>
Another important point to remember is that the number of data partitions cannot be changed. We do support an online redistribution ( rebalancing ) of partitions. In other words inclusion of new nodes results in moving ownership of partitions, but the total number of partitions will always remain the same, as will the mapping of key to partition. This means it is important to give a good number of partitions to start with. The script <a href="https://github.com/voldemort/voldemort/blob/master/bin/generate_cluster_xml.py">here</a> will generate this part of the config for you.
</p>

<p>
Note that the configuration is currently simple files so it is important that the data in cluster.xml and stores.xml be exactly the same on each server, and that the node ids and partitions not be changed, since that can mean that clients will think their data should be on node <i>X</i> when really it was stored on node <i>Y</i>. This limitation will be removed as the configuration is moved into voldemort itself.
</p>

<h3>Store configuration</h3>

<p>Here is an examples stores.xml for a store named test, that requires only a single read and write and uses bdb for persistence:</p>

<pre>
	&lt;stores&gt;
	    &lt;store&gt;
	        &lt;name&gt;test&lt;/name&gt;
	        &lt;replication-factor&gt;2&lt;/replication-factor&gt;
	        &lt;preferred-reads&gt;2&lt;/preferred-reads&gt;
	        &lt;required-reads&gt;1&lt;/required-reads&gt;
	        &lt;preferred-writes&gt;2&lt;/preferred-writes&gt;
	        &lt;required-writes&gt;1&lt;/required-writes&gt;
	        &lt;persistence&gt;bdb&lt;/persistence&gt;
	        &lt;routing&gt;client&lt;/routing&gt;
	        &lt;routing-strategy&gt;consistent-routing&lt;/routing-strategy&gt;
	        &lt;key-serializer&gt;
	            &lt;type&gt;string&lt;/type&gt;
	            &lt;schema-info&gt;utf8&lt;/schema-info&gt;
	        &lt;/key-serializer&gt;
	        &lt;value-serializer&gt;
	            &lt;type&gt;json&lt;/type&gt;
	            &lt;schema-info version="1"&gt;[{"id":"int32", "name":"string"}]&lt;/schema-info&gt;
	            &lt;compression&gt;
			&lt;type&gt;gzip&lt;type&gt;
		    &lt;/compression&gt;
	        &lt;/value-serializer&gt;
	    &lt;/store&gt;
	&lt;/stores&gt;
</pre>
<p>
Each of these parameters deserves a quick discussion:
<ul>
	<li><i>name</i>&mdash; The name of the store. This is the string by which clients will be able to connect and operate on this store. It is equivalent to the table name in sql.</li>
	<li><i>replication-factor</i>&mdash; This is the total number of times the data is stored. Each put or delete operation must eventually hit this many nodes. A replication factor of <i>n</i> means it can be possible to tolerate up to <i>n</i> - 1 node failures without data loss.</li>
	<li><i>preferred-reads</i> (optional)&mdash;The number of successful reads the client will attempt to do before returning a value to the application. This defaults to be equal to required reads</li>
	<li><i>required-reads</i>&mdash;The least number of reads that can succeed without throwing an exception. Consider a case where the replication factor is 5, preferred reads is 4, and required reads is 2. If 3 of the 5 nodes are operational then the client may try all the nodes to try to reach the preferred 4 reads, but since only 3 are responsive it will allow the read to complete. Had only 1 been responsive it would have thrown an exception, since that was lower than the consistency guarantee requested for this table (and that could mean returning stale data).</li>
	<li><i>preferred-writes</i>(optional)&mdash;The number of successful writes the client attempts to block for before returning success. Defaults to required-writes</li>
	<li><i>required-writes</i>&mdash; The least number of writes that can succeed without the client getting back an exception.</li>
	<li><i>persistence</i>&mdash; The persistence backend used by the store. Currently this could be one of <i>bdb</i>, <i>mysql</i>, <i>memory</i>, <i>readonly</i>, and <i>cache</i>. The difference between <i>cache</i> and <i>memory</i> is that <i>memory</i> will throw and OutOfMemory exception if it grows larger than the JVM heap whereas <i>cache</i> will discard data.</li>
	<li><i>routing</i>&mdash; Determines the routing policy. We support both <i>client</i> ( Client side routing ) and <i>server</i> ( Server side routing ).</li>
	<li><i>routing-strategy</i>&mdash; Determines how we store the replicas. Currently we support three routing-strategies - <i>consistent-routing</i> (default), <i>zone-routing</i> and <i>all-routing</i>.</li>
	<li><i>key-serializer</i>&mdash; The serialization type used for reading and writing <i>keys</i>. The type can be <i>json</i>, <i>java-serialization</i>, <i>string</i>, <i>protobuff</i>, <i>thrift</i>, or <i>identity</i> (meaning raw bytes). The schema-info gives information to the serializer about how to perform the mapping (e.g. the JSON schema described in <a href="design.php">here</a>).
	</li>
	<li><i>value-serializer</i>&mdash; The serialization type used for reading and writing <i>values</i>. The supported types are the same as for keys. In the above example we also highlight the subelement 'compression' which currently supports 'gzip' and 'lzf' compression. The subelements are same as for the key-serializer, except that the the value serializer can have multiple schema-infos with different versions. The highest version is the one used for writing data, but data is always read with the version it was written with. This allows for gradual schema evolution. Versioning is only supported by the JSON serializer as other serialization formats have their own versioning systems.
		Here are some example serializers:
			<pre>
    &lt;!-- A serializer that serializes plain strings in UTF8 encoding --&gt;
    &lt;value-serializer&gt;
        &lt;type&gt;string&lt;/type&gt;
        &lt;schema-info&gt;utf8&lt;/schema-info&gt;
    &lt;/value-serializer&gt;

    &lt;!-- A serializer that serializes binary-format JSON data with the given schema. 
            Each value is a List&lt;Map&lt;String, ?&gt;&gt; where the keys "id" and "name" and the values 
            are a 32-bit integer id and a string name. --&gt;
    &lt;value-serializer&gt;
        &lt;type&gt;json&lt;/type&gt;
        &lt;schema-info&gt;[{"id":"int32", "name":"string"}]&lt;/schema-info&gt;
    &lt;/value-serializer&gt;
		
    &lt;!-- A serializer that serializes protocol buffer objects of the given class. --&gt;
    &lt;value-serializer&gt;
        &lt;type&gt;protobuff&lt;/type&gt;
        &lt;schema-info&gt;java=com.something.YourProtoBuffClassName&lt;/schema-info&gt;
    &lt;/value-serializer&gt;
		
    &lt;!-- A serializer that serializes thrift generated objects using one of the 
            following protocols - 'binary', 'json' or 'simple-json'.
	    Current support for Java clients only. --&gt;
    &lt;value-serializer&gt;
        &lt;type&gt;thrift&lt;/type&gt;
        &lt;schema-info&gt;java=com.something.YourThriftClassName,protocol=binary&lt;/schema-info&gt;
    &lt;/value-serializer&gt;
			
    &lt;!-- Avro serialization - either 'avro-generic', 'avro-specific' or 'avro-reflective' --&gt;
    &lt;value-serializer&gt;
        &lt;type&gt;avro-generic&lt;/type&gt;
        &lt;schema-info&gt;{"name": "Kind", "type": "enum", "symbols": ["FOO","BAR"]}&lt;/schema-info&gt;
    &lt;/value-serializer&gt;
			</pre>
		</li>
        </li>
        <li><i>retention-days</i> (optional)&mdash; This optional parameter allows you to set a retention property to your data. Then every day, at a specified time on the servers, a scheduled job will be run to delete all data having timestamp > retention-days. This is useful to keep your data trimmed. </li>
        <li><i>retention-scan-throttle-rate</i> (optional)&mdash; If <i>retention-days</i> is specified this is the rate at which we'll scan the tuples to delete data.</li>
</ul>
</p>
<p>
If you intend to use the <i>zone-routing</i> strategy we need to extend the store definition to tell it how to replicate w.r.t. zones. Here is an example of a store definition with 'zone-routing' enabled.
<pre>
	&lt;stores&gt;
	    &lt;store&gt;
	        &lt;name&gt;test&lt;/name&gt;
	        ...
                &lt;routing-strategy&gt;zone-routing&lt;/routing-strategy&gt;
                &lt;!-- This number should be total of individual zone-replication-factor's --&gt;
                &lt;replication-factor&gt;2&lt;/replication-factor&gt;
	        &lt;zone-replication-factor&gt;
	            &lt;replication-factor zone-id="0"&gt;1&lt;/replication-factor&gt;
	            &lt;replication-factor zone-id="1"&gt;1&lt;/replication-factor&gt;
	        &lt;/zone-replication-factor&gt;
	        &lt;zone-count-reads&gt;0&lt;/zone-count-reads&gt;
	        &lt;zone-count-writes&gt;0&lt;/zone-count-writes&gt;
	        &lt;hinted-handoff-strategy&gt;proximity-handoff&lt;/hinted-handoff-strategy&gt;
	        ... 
           &lt;/store&gt;
	&lt;/stores&gt;
</pre>
 The important change here is the introduction of <i>zone-replication-factor</i> which should contain a replication factor that you would want in every zone. Other parameters :
<ul>
	<li><i>zone-count-*</i>&mdash; The number of zones we want to block for during reads / writes before we return the request. The number <i>0</i> means we'll block for atleast one request from the <i>local</i> zone only. The number <i>1</i> means we'll block for atleast one request from one other zone.</li> 
        <li><i>hinted-handoff-strategy</i> (optional) &mdash; Another consistency mechanism which we've added recently is <a href="https://github.com/voldemort/voldemort/wiki/Hinted-Handoff">Hinted handoff</a>. We can turn on this feature on a per store basis. This parameter defines the strategy we would use to decide which live nodes to write our "hint" to. The various options are <i>any-handoff</i>, <i>consistent-handoff</i> and <i>proximity-handoff</i>. 
</ul>
</p>

<h3>Per-node configuration</h3>

<p>We store per-node based configuration in the server.properties file. Most of the properties have sane defaults ( hopefully ). The bare minimal file should have the following property.</p> 

<pre>
	# The ID of *this* particular cluster node (different for each node in cluster)
	node.id=0
</pre>

Here is a list of all the configuration options supported:

<?php include('includes/props.php'); ?>

<h3>BDB Management</h3>

<p>The underlying key-value store is also important for configuration and operation management. If BDB is used then all configuration is done through the server.properties file. If MySQL is used then usual mysql administration must be done.</p>

<p>Oracle has <a href="http://download.oracle.com/docs/cd/E17277_02/html/GettingStartedGuide/backuprestore.html">a writeup</a> that gives a good overview of the operational side of BDB.</p>

<h3>Client configuration</h3>

<p>The above settings were all for the server. It is important to correctly configure the client as well. Following is a list of configuration options for the clients:

<?php include('includes/client_props.php'); ?>

<h3>Some additional suggestions</h3>

<h4>JVM Settings</h4>

At LinkedIn we maintain two sets of clusters, read-only and read-write. The read-write clusters are clusters using BDB stores and have totally different JVM characteristics from those using read-only stores. Here is what we use at LinkedIn for our read-write stores:

<pre>
	# Min, max, total JVM size 
	JVM_SIZE="-server -Xms22g -Xmx22g"

	# New Generation Sizes 
	JVM_SIZE_NEW="-XX:NewSize=2048m -XX:MaxNewSize=2048m"

	# Type of Garbage Collector to use
	JVM_GC_TYPE="-XX:+UseConcMarkSweepGC -XX:+UseParNewGC"

	# Tuning options for the above garbage collector
	JVM_GC_OPTS="-XX:CMSInitiatingOccupancyFraction=70 -XX:SurvivorRatio=2"

	# JVM GC activity logging settings
	JVM_GC_LOG="-XX:+PrintTenuringDistribution -XX:+PrintGCDetails -XX:+PrintGCDateStamps -Xloggc:$LOG_DIR/gc.log"
</pre>

This is the setup on a 32GB RAM box with a BDB cache size of 10GB and 3 cleaner threads. There are two key things here: (1) BDB cache must fit in heap or else it won't work (obviously), (2) you must use the concurrent mark and sweep gc or else the GC pauses from collecting such a large heap will cause unresponsive periods (it doesn't happen at first either, it creeps up and then eventually goes into a spiral of gc pause death). 
<p>
For the read-only clusters we use the same JVM GC settings, except the heap size is set to a smaller value.
</p>
<pre>
	# Min, max, total JVM size 
	JVM_SIZE="-server -Xms4096m -Xmx4096m"
</pre>

This is done because in the case of read-only stores we rely on the OS page cache and don't really want our JVM heap to take up space. 
<?php require "../includes/footer.php" ?>
