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
[2008-08-11 17:00:32,884] INFO Starting voldemort-server (voldemort.server.VoldemortService)
[2008-08-11 17:00:32,886] INFO Starting all services:  (voldemort.server.VoldemortServer)
[2008-08-11 17:00:32,886] INFO Starting storage-service (voldemort.server.VoldemortService)
[2008-08-11 17:00:32,891] INFO Initializing stores: (voldemort.server.storage.StorageService)
[2008-08-11 17:00:32,891] INFO Opening test. (voldemort.server.storage.StorageService)
[2008-08-11 17:00:32,903] INFO All stores initialized. (voldemort.server.storage.StorageService)
[2008-08-11 17:00:32,903] INFO Starting scheduler (voldemort.server.VoldemortService)
[2008-08-11 17:00:32,906] INFO Scheduling pusher to run every 60000 milliseconds. (voldemort.server.scheduler.SchedulerService)
[2008-08-11 17:00:32,909] INFO Starting http-service (voldemort.server.VoldemortService)
[2008-08-11 17:00:33,044] INFO Starting socket-service (voldemort.server.VoldemortService)
[2008-08-11 17:00:33,044] INFO Starting voldemort socket server on port 6666. (voldemort.server.socket.SocketServer)
[2008-08-11 17:00:33,045] INFO Starting JMX Service (voldemort.server.VoldemortService)
[2008-08-11 17:00:33,133] INFO All services started. (voldemort.server.VoldemortServer)
</pre>

<p>Alternately we can give VOLDEMORT_HOME on the command line and avoid having to set an environment variable</p>

<pre>
$ ./bin/voldemort-server.sh /path/to/voldemort
[2008-08-11 17:00:32,884] INFO Starting voldemort-server (voldemort.server.VoldemortService)
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