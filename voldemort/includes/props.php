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
	<td>enable.bdb.engine</td>
	<td>true</td>
	<td>Should the BDB engine be enabled?</td>
</tr>
<tr>
	<td>bdb.cache.size</td>
	<td>200MB (make it bigger!!!)</td>
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
	<td>enable.memory.engine</td>
	<td>true</td>
	<td>Should we enable the memory storage engine? Might as well this takes no resources and is just here for consistency.</td>
</tr>
<tr>
	<td>enable.cache.engine</td>
	<td>true</td>
	<td>Should we enable the cache storage engine? Might as well this takes no resources and is just here for consistency.</td>
</tr>
<tr>
	<td>enable.readonly.engine</td>
	<td>false</td>
	<td>Should we enable the readonly storage engine?</td>
</tr>
<tr>
	<td>readonly.file.wait.timeout.ms</td>
	<td>4000</td>
	<td>The maximum time to wait to acquire a filehandle to perform reads.</td>
</tr>
<tr>
	<td>readonly.backups</td>
	<td>1</td>
	<td>The number of backup copies of the data to keep around for rollback.</td>
</tr>
<tr>
	<td>readonly.file.handles</td>
	<td>5</td>
	<td>The number of file descriptors to pool per store.</td>
</tr>
<tr>
	<td>readonly.data.directory</td>
	<td>${data.directory}/read-only</td>
	<td>The directory in which to store readonly data files.</td>
</tr>
<tr>
	<td>slop.store.engine</td>
	<td>bdb</td>
	<td>What storage engine should we use for storing misdelivered messages that need to be rerouted?</td>
</tr>
<tr>
	<td>max.threads</td>
	<td>100</td>
	<td>The maximum number of threads the server can use.</td>
</tr>
<tr>
	<td>core.threads</td>
	<td>max(1, ${max.threads} / 2)</td>
	<td>The number of threads to keep alive even when idle.</td>
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
	<td>slop.detection.enable</td>
	<td>false</td>
	<td>Enable detection of misdelivered messages for persistence and redelivery.</td>
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
	<td>enable.gossip</td>
	<td>false</td>
	<td>Enable gossip to synchronize state</td>
</tr>
<tr>
	<td>pusher.poll.ms</td>
	<td>2 * 60 * 1000</td>
	<td>How often should misdelivered "slop" data be pushed out to nodes?</td>
</tr>
<tr>
	<td>scheduler.threads</td>
	<td>3</td>
	<td>The number of threads to use for scheduling periodic jobs</td>
</tr>
<tr>
	<td>admin.enable</td>
	<td>true</td>
	<td>Enable the Admin service?</td>
</tr>
<tr>
	<td>admin.max.threads</td>
	<td>20</td>
	<td>Max Number of threads used by Admin services</td>
</tr>
<tr>
	<td>admin.core.threads</td>
	<td>max(1, ${admin.max.threads} / 2)</td>
	<td>The number of threads to keep alive by Admin service even when idle</td>
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
	<td>enable.rebalancing</td>
	<td>true</td>
	<td>Enable rebalance service?</td>
</tr>
<tr>
	<td>max.rebalancing.attempts</td>
	<td>3</td>
	<td>Number of attempts made during rebalancing</td>
</tr>
</table>
