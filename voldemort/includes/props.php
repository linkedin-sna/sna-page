<table class="data-table">
<tr>
	<th>name</th>
	<th>default</th>
	<th>description</th>
</tr>
<tr>
 	<td>node.id</td>
        <td>none</td>
        <td>The unique, sequential identifier for this server in the cluster (starts with 0)</td>
</tr>
<tr>
	<td>voldemort.home</td>
	<td>none</td>
	<td>The base directory for voldemort. Can also be specified via the environment variable VOLDEMORT_HOME or via a command line option.</td>
</tr>
<tr>
	<td>data.directory</td>
	<td>${voldemort.home}/data</td>
	<td>The directory where voldemort data is stored</td>
</tr>
<tr>
	<td>metadata.directory</td>
	<td>${voldemort.home}/config</td>
	<td>The directory where voldemort configuration is stored</td>
</tr>
<tr>
 	<th colspan="3">BDB stores configuration</th>
</tr>
<tr>
	<td>enable.bdb.engine</td>
	<td>true</td>
	<td>Should the BDB engine be enabled?</td>
</tr>
<tr>
	<td>bdb.cache.size</td>
	<td>200MB</td>
	<td>The BDB cache that is shared by all BDB tables. Bigger is better.</td>
</tr>
<tr>
	<td>bdb.write.transactions</td>
	<td>false</td>
	<td>Should transactions be immediately written to disk?</td>
</tr>
<tr>
	<td>bdb.flush.transactions</td>
	<td>false</td>
	<td>When the transaction has been written to disk should we force the disk to flush the OS cache. This is a fairly expensive operation.</td>
</tr>
<tr>
	<td>bdb.data.directory</td>
	<td>${data.directory}/bdb</td>
	<td>The directory where the BDB environment is located</td>
</tr>
<tr>
	<td>bdb.max.logfile.size</td>
	<td>1GB</td>
	<td>The size of an individual log file</td>
</tr>
<tr>
	<td>bdb.btree.fanout</td>
	<td>512</td>
	<td>The fanout size for the btree. Bigger fanout more effienciently supports larger btrees.</td>
</tr>
<tr>
	<td>bdb.checkpoint.interval.bytes</td>
	<td>20 * 1024 * 1024</td>
	<td>How often (in bytes) should we checkpoint the transaction log? Checkpoints make startup and shutdown faster.</td>
</tr>
<tr>
	<td>bdb.checkpoint.interval.ms</td>
	<td>30000</td>
	<td>How often in ms should we checkpoint the transaction log</td>
</tr>
<tr>
	<td>bdb.one.env.per.store</td>
	<td>false</td>
	<td>Use one BDB environment for every store</td>
</tr>
<tr>
	<td>bdb.cleaner.threads</td>
	<td>1</td>
	<td>Number of BDB cleaner threads</td>
</tr>
<tr>
     	<th colspan="3">MySQL stores configuration</th>
</tr>
<tr>
	<td>enable.mysql.engine</td>
	<td>false</td>
	<td>Should we enabled the mysql storage engine? Doing so will create a connection pool that will be used for the mysql instance</td>
</tr>
<tr>
	<td>mysql.user</td>
	<td>root</td>
	<td>The mysql username to user</td>
</tr>
<tr>
	<td>mysql.password</td>
	<td></td>
	<td>The mysql password to user</td>
</tr>
<tr>
	<td>mysql.host</td>
	<td>localhost</td>
	<td>The host of the mysql instance</td>
</tr>
<tr>
	<td>mysql.port</td>
	<td>3306</td>
	<td>The port of the mysql instance</td>
</tr>
<tr>
	<td>mysql.database</td>
	<td>voldemort</td>
	<td>The name of the mysql database</td>
</tr>
<tr>
     	<th colspan="3">Read-only stores configuration</th>
</tr>
<tr>
	<td>enable.readonly.engine</td>
	<td>false</td>
	<td>Should we enable the readonly storage engine?</td>
</tr>
<tr>
	<td>readonly.backups</td>
	<td>1</td>
	<td>The number of backup copies of the data to keep around for rollback.</td>
</tr>
<tr>
	<td>readonly.search.strategy</td>
	<td>BinarySearchStrategy</td>
	<td>Class name of search strategy to use while finding key. We support BinarySearchStrategy and InterpolationSearchStrategy</td>
</tr>
<tr>
	<td>readonly.data.directory</td>
	<td>${data.directory}/read-only</td>
	<td>The directory in which to store readonly data files.</td>
</tr>
<tr>
	<td>readonly.delete.backup.ms</td>
	<td>0</td>
	<td>Millisecond we wait for before deleting old data. Useful to decreasing IO during swap.</td>
</tr>
<tr>
 	<th colspan="3">Slop store configuration</th>
</tr>
<tr>
	<td>slop.enable</td>
	<td>true</td>
	<td>Do we want to initialize a storage engine for slops + have the job enabled?</td>
</tr>
<tr>
	<td>slop.store.engine</td>
	<td>bdb</td>
	<td>What storage engine should we use for storing misdelivered messages that need to be rerouted?</td>
</tr>
<tr>
	<td>slop.pusher.enable</td>
	<td>true</td>
	<td>Enable the slop pusher job which pushes every 'slop.frequency.ms' ms ( Prerequisite - slop.enable=true )</td>
</tr>
<tr>
	<td>slop.read.byte.per.sec</td>
	<td>10 * 1000 * 1000</td>
	<td>Slop max read throughput </td>
</tr>
<tr>
	<td>slop.write.byte.per.sec</td>
	<td>10 * 1000 * 1000</td>
	<td>Slop max write throughput </td>
</tr>
<tr>
	<td>pusher.type</td>
	<td>StreamingSlopPusherJob</td>
	<td>Job type to use for pushing out the slops</td>
</tr>
<tr>
	<td>slop.frequency.ms</td>
	<td>5 * 60 * 1000</td>
	<td>Frequency at which we'll try to push out the slops </td>
</tr>
<tr>
	<th colspan="3">Rebalancing configuration</th>
</tr>
<tr>
	<td>enable.rebalancing</td>
	<td>true</td>
	<td>Enable rebalance service?</td>
</tr>
<tr>
	<td>max.rebalancing.attempts</td>
	<td>3</td>
	<td>Number of attempts the server side rebalancer makes to fetch data</td>
</tr>
<tr>
	<td>rebalancing.timeout.seconds</td>
	<td>10 * 24 * 60 * 60</td>
	<td>Time we give for the server side rebalancing to finish copying data</td>
</tr>
<tr>
	<td>max.parallel.stores.rebalancing</td>
	<td>3</td>
	<td>Stores to rebalancing in parallel</td>
</tr>
<tr>
	<td>rebalancing.optimization</td>
	<td>true</td>
	<td>Should we run our rebalancing optimization for non-partition aware stores?</td>
</tr>
<tr>
	<th colspan="3">Retention configuration</th>
</tr>
<tr>
	<td>retention.cleanup.first.start.hour</td>
	<td>0</td>
	<td>Hour when we want to start the first retention cleanup job</td>
</tr>
<tr>
	<td>retention.cleanup.period.hours</td>
	<td>24</td>
	<td>Run the retention clean up job every n hours</td>
</tr>
<tr>
	<th colspan="3">Gossip configuration</th>
<tr>
<tr>
	<td>enable.gossip</td>
	<td>false</td>
	<td>Enable gossip to synchronize state</td>
</tr>
<tr>
	<td>gossip.interval.ms</td>
	<td>30*1000</td>
	<td>Enable gossup every n ms</td>
</tr>
<tr>
	<th colspan="3">Admin service</th>
</tr>
<tr>
	<td>admin.enable</td>
	<td>true</td>
	<td>Enable the Admin service?</td>
</tr>
<tr>
	<td>admin.max.threads</td>
	<td>20</td>
	<td>Max Number of threads used by Admin services. Used by BIO ( i.e. if enable.nio.connector = false )</td>
</tr>
<tr>
	<td>admin.core.threads</td>
	<td>max(1, ${admin.max.threads} / 2)</td>
	<td>The number of threads to keep alive by Admin service even when idle. Used by BIO ( i.e. if enable.nio.connector = false )</td>
</tr>
<tr>
	<td>nio.admin.connector.selectors</td>
	<td>max ( 8, number of processors )</td>
	<td>Number of selector threads for admin operations. Used by NIO ( i.e. if enable.nio.connector = true )</td>
</tr>
<tr>
 	<th colspan="3">Core Voldemort server configuration</th>
<tr>
<tr>
	<td>enable.nio.connector</td>
	<td>false</td>
	<td>Enable NIO on server side</td>
</tr>
<tr>
	<td>nio.connector.selectors</td>
	<td>max ( 8, number of processors )</td>
	<td>Number of selector threads for normal operations. Used by NIO ( i.e. if enable.nio.connector = true )</td>
</tr>
<tr>
	<td>max.threads</td>
	<td>100</td>
	<td>The maximum number of threads the server can use ( Used by HTTP and BIO - enable.nio.connector = false -  service only )</td>
</tr>
<tr>
	<td>core.threads</td>
	<td>max(1, ${max.threads} / 2)</td>
	<td>The number of threads to keep alive even when idle ( Used by HTTP and BIO - enable.nio.connector = false -  service only )</td>
</tr>
<tr>
	<td>socket.timeout.ms</td>
	<td>4000</td>
	<td>The socket SO_TIMEOUT. Essentially the amount of time to block on a low-level network operation before throwing an error.</td>
</tr>
<tr>
	<td>routing.timeout.ms</td>
	<td>5000</td>
	<td>The total amount of time to wait for adequate responses from all nodes before throwing an error.</td>
</tr>
<tr>
	<td>stream.read.byte.per.sec</td>
	<td>10 * 1000 * 1000</td>
	<td>Max read throughput allowed when Admin service streams data</td>
</tr>
<tr>
	<td>stream.write.byte.per.sec</td>
	<td>10 * 1000 * 1000</td>
	<td>Max write throughput allowed when Admin service streams data</td>
</tr>
<tr>
	<td>http.enable</td>
	<td>true</td>
	<td>Enable the HTTP data server?</td>
</tr>
<tr>
	<td>socket.enable</td>
	<td>true</td>
	<td>Enable the socket data server?</td>
</tr>
<tr>
	<td>jmx.enable</td>
	<td>true</td>
	<td>Enable JMX monitoring?</td>
</tr>
<tr>
	<td>enable.verbose.logging</td>
	<td>true</td>
	<td>Log every operation on all stores.</td>
</tr>
<tr>
	<td>enable.stat.tracking</td>
	<td>true</td>
	<td>Track load statistics on the stores.</td>
</tr>
<tr>
	<td>scheduler.threads</td>
	<td>6</td>
	<td>Number of threads to use for scheduled jobs</td>
</tr>
</table>
