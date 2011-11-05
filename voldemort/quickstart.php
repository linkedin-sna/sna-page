<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>Quickstart</h1>

<h4>Step 1: Download the code</h4> 
Download either <a href="http://github.com/voldemort/voldemort/downloads">a recent stable release</a></h4> or, for those who like to live more dangerously, the up-to-the-minute build from <a href="http://test.project-voldemort.com:8080/job/voldemort-master/lastBuild">the build server</a>.

<h4>Step 2: Start single node cluster</h4>
<pre>
	> bin/voldemort-server.sh config/single_node_cluster > /tmp/voldemort.log &
</pre>

<h4>Step 3: Start commandline test client and do some operations</h4>
<pre>
	> bin/voldemort-shell.sh test tcp://localhost:6666
	Established connection to test via tcp://localhost:6666
	> put "hello" "world"
	> get "hello"
	version(0:1): "world"
	> delete "hello"
	> get "hello"
	null
	> exit
	k k thx bye.
</pre>

<h1>More details</h1>

<h2>Client</h2>

<p>Here is an example showing how to connect to a store as a client to do reads and writes from Java:</p>

<pre>
 String bootstrapUrl = "tcp://localhost:6666";
 StoreClientFactory factory = new SocketStoreClientFactory(new ClientConfig().setBootstrapUrls(bootstrapUrl));
 
 // create a client that executes operations on a single store
 StoreClient<String, String> client = factory.getStoreClient("my_store_name");
</pre>

<p>After initializing the store client for every store once we can reuse it to run our queries as follows:</p>

<pre> 
 // do some random pointless operations
 Versioned<String> value = client.get("some_key");
 value.setObject("some_value");
 client.put("some_key", value);
</pre>

<p>Note that StoreClient is just an interface, so for the purpose of unit testing we can completely mock the storage layer. This is something that is essentially impossible to do with a normal relational db since sql is the interface and it is vendor specific.</p>

<h2>Server</h2>

<p>There are three methods for using the server:</p>

<h4>1. Start from the command line</h4>

You must first build the jar file using ant, as described above, then do the following:
<pre>
$ VOLDEMORT_HOME='/path/to/voldemort'
$ cd $VOLDEMORT_HOME
$ ./bin/voldemort-server.sh
[2011-07-14 18:06:24,921 voldemort.store.metadata.MetadataStore] INFO metadata init(). 
[2011-07-14 18:06:25,309 voldemort.server.VoldemortServer] INFO Using NIO Connector. 
[2011-07-14 18:06:25,331 voldemort.server.VoldemortServer] INFO Using NIO Connector for Admin Service. 
[2011-07-14 18:06:25,332 voldemort.server.VoldemortService] INFO Starting voldemort-server 
[2011-07-14 18:06:25,333 voldemort.server.VoldemortServer] INFO Starting 8 services. 
[2011-07-14 18:06:25,333 voldemort.server.VoldemortService] INFO Starting storage-service 
[2011-07-14 18:06:25,399 voldemort.server.storage.StorageService] INFO Initializing bdb storage engine. 
[2011-07-14 18:06:25,404 voldemort.server.storage.StorageService] INFO Initializing read-only storage engine. 
[2011-07-14 18:06:25,406 voldemort.server.storage.StorageService] INFO Initializing the slop store using bdb 
[2011-07-14 18:06:25,767 voldemort.server.storage.StorageService] INFO Initializing stores: 
[2011-07-14 18:06:25,767 voldemort.server.storage.StorageService] INFO Opening store 'test' (bdb). 
[2011-07-14 18:06:25,834 voldemort.server.storage.StorageService] INFO All stores initialized. 
[2011-07-14 18:06:25,834 voldemort.server.VoldemortService] INFO Starting scheduler-service 
[2011-07-14 18:06:25,834 voldemort.server.VoldemortService] INFO Starting async-scheduler 
[2011-07-14 18:06:25,834 voldemort.server.VoldemortService] INFO Starting http-service 
[2011-07-14 18:06:26,092 voldemort.server.VoldemortService] INFO Starting socket-service 
[2011-07-14 18:06:26,101 voldemort.server.VoldemortService] INFO Starting rebalance-service 
[2011-07-14 18:06:26,109 voldemort.server.VoldemortService] INFO Starting jmx-service 
[2011-07-14 18:06:26,142 voldemort.server.VoldemortServer] INFO Startup completed in 809 ms. 
</pre>

<p>Alternately we can give VOLDEMORT_HOME on the command line and avoid having to set an environment variable</p>

<pre>
$ ./bin/voldemort-server.sh /path/to/voldemort
[2011-07-14 18:06:24,921 voldemort.store.metadata.MetadataStore] INFO metadata init(). 
[2011-07-14 18:06:25,309 voldemort.server.VoldemortServer] INFO Using NIO Connector. 
[2011-07-14 18:06:25,331 voldemort.server.VoldemortServer] INFO Using NIO Connector for Admin Service. 
[2011-07-14 18:06:25,332 voldemort.server.VoldemortService] INFO Starting voldemort-server 
...
</pre>

<h4>2. Embedded Server</h4>

<p>You can instantiate the server directly in your code.</p>

<pre>
VoldemortConfig config = VoldemortConfig.loadFromEnvironmentVariable();
VoldemortServer server = new VoldemortServer(config);
server.start();
</pre>

<h4>3. Deploy as a war</h4>
<p>To do this build the war file using the <pre>ant war</pre> target and deploy via whatever mechanism your servlet container supports.</p>

<?php require "../includes/footer.php" ?>
