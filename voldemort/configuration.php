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

<p>Here is an example cluster.xml for a 2-node cluster with 8 data partitions:</p>

<pre>	
  &lt;cluster&gt;
    &lt!-- The name is just to help users identify this cluster from the gui --&gt
    &lt;name&gt;mycluster&lt;/name&gt;
    &lt;server&gt;
      &lt!-- The node id is a unique, sequential id beginning with 0 that identifies each server in the cluster--&gt
      &lt;id&gt;0&lt;/id&gt;
      &lt;host&gt;vldmt1.prod.linkedin.com&lt;/host&gt;
      &lt;http-port&gt;8081&lt;/http-port&gt;
      &lt;socket-port&gt;6666&lt;/socket-port&gt;
      &lt;admin-port&gt;6667&lt;/admin-port&gt;
      &lt;!-- A list of data partitions assigned to this server --&gt;
      &lt;partitions&gt;0,1,2,3&lt;/partitions&gt;
    &lt;/server&gt;
    &lt;server&gt;
      &lt;id&gt;1&lt;/id&gt;
      &lt;host&gt;vldmt2.prod.linkedin.com&lt;/host&gt;
      &lt;http-port&gt;8081&lt;/http-port&gt;
      &lt;socket-port&gt;6666&lt;/socket-port&gt;
      &lt;admin-port&gt;6667&lt;/admin-port&gt;
      &lt;partitions&gt;4,5,6,7&lt;/partitions&gt;
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
	<li><i>routing</i>&mdash; Determines the routing policy. Currently only client-side routing is fully supported. Server side routing will be coming soon, as will a few more interesting policies.</li>
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
</ul>
</p>

<h3>Per-node configuration</h3>

<p>Here is an example server.properties for node 0 in the cluster. Most properties have (hopefully) sane defaults and can be skipped. Here is a minimal server.properties:</p>

<pre>
	# The ID of *this* particular cluster node (different for each node in cluster)
	node.id=0
</pre>

Here is a list of all the configuration options supported:

<?php include('includes/props.php'); ?>

<h3>BDB Management</h3>

<p>The underlying key-value store is also important for configuration and operation management. If BDB is used then all configuration is done through the server.properties file. If MySQL is used then usual mysql administration must be done.</p>

<p>Oracle has <a href="http://www.oracle.com/technology/documentation/berkeley-db/je/GettingStartedGuide/backuprestore.html">a writeup</a> that gives a good overview of the operational side of BDB.</p>

<h3>Client configuration</h3>

<p>The above settings were all for the server. It is important to correctly configure the client as well. Following is a list of configuration options for the clients:

<?php include('includes/client_props.php'); ?>

<h3>Some additional suggestions</h3>

<h4>JVM Settings</h4>

Since the Voldemort servers will likely have fairly large heap sizes, getting good JVM garbage collector settings is important. Here is what we use at LinkedIn, with some success:

<pre>
	# Min, max, total JVM size (-Xms -Xmx)
	JVM_SIZE="-server -Xms12g -Xmx12g"

	# New Generation Sizes (-XX:NewSize -XX:MaxNewSize)
	JVM_SIZE_NEW="-XX:NewSize=2048m -XX:MaxNewSize=2048m"

	# Type of Garbage Collector to use
	JVM_GC_TYPE="-XX:+UseConcMarkSweepGC -XX:+UseParNewGC"

	# Tuning options for the above garbage collector
	JVM_GC_OPTS="-XX:CMSInitiatingOccupancyFraction=70"

	# JVM GC activity logging settings ($LOG_DIR set in the ctl script)
	JVM_GC_LOG="-XX:+PrintTenuringDistribution -XX:+PrintGCDetails -XX:+PrintGCTimeStamps -Xloggc:$LOG_DIR/gc.log"
</pre>

This setup was used with an 8GB BDB cache. There are two key things here: (1) BDB cache must fit in heap or else it won't work (obviously), (2) you must use the concurrent mark and sweep gc or else the GC pauses from collecting such a large heap will cause unresponsive periods (it doesn't happen at first either, it creeps up and then eventually goes into a spiral of gc pause death). 

<?php require "../includes/footer.php" ?>
