<table class="data-table">
<tr>
	<th>name</th>
	<th>default</th>
	<th>description</th>
</tr>
<tr>
	<td>max_connections</td>
        <td>50</td>
        <td>Maximum number of connection allowed to each voldemort node</td>
</tr>
<tr>
	<td>max_threads</td>
	<td>5</td>
	<td>The maximum number of client threads ( Used by the client thread pool )</td>
</tr>
<tr>
	<td>max_queued_requests</td>
	<td>50</td>
	<td>The maximum number of queued node operations before client actions will be blocked ( Used by the client thread pool )</td>
</tr>
<tr>
	<td>thread_idle_ms</td>
	<td>100000</td>
	<td>The amount of time to keep an idle client thread alive ( Used by the client thread pool )</td>
</tr>
<tr>
	<td>connection_timeout_ms</td>
	<td>500</td>
	<td>Set the maximum allowable time to block waiting for a free connection</td>
</tr>
<tr>
	<td>socket_timeout_ms</td>
	<td>5000</td>
	<td>Maximum amount of time the socket will block waiting for network activity</td>
</tr>
<tr>
	<td>routing_timeout_ms</td>
	<td>15000</td>
	<td>Set the timeout for all blocking operations to complete on all nodes. The number of blocking operations can be configured using the preferred-reads and preferred-writes configuration for the store.</td>
</tr>
<tr>
	<td>selectors</td>
	<td>8</td>
	<td>Number of selectors used for multiplexing requests in our NIO client</td>
</tr>
<tr>
	<td>socket_buffer_size</td>
	<td>64 * 1024</td>
	<td>Set the size of the socket buffer (in bytes) to use for both socket reads and socket writes</td>
</tr>
<tr>
	<td>enable_jmx</td>
	<td>true</td>
	<td>Enable JMX monitoring</td>
</tr>
<tr>
	<td>enable_pipeline_routed_store</td>
	<td>true</td>
	<td>Use the new pipeline routed store for client side routing</td>
</tr>
<tr>
	<td>max_bootstrap_retries</td>
	<td>2</td>
	<td>Number of times we'll try to connect to bootstrap url</td>
</tr>
<tr>
	<td>bootstrap_urls</td>
	<td>Compulsory parameter</td>
	<td>Comma separated list of URLs to use as bootstrap servers</td>
</tr>
<tr>
	<td>serializer_factory_class</td>
	<td>Default serializer factory with support for avro, pb, java, etc.</td>
	<td>Custom serializer factory class name</td>
</tr>
<tr>
	<td>client_zone_id</td>
	<td>0</td>
	<td>Zone id where the client resides. Used to make smarter routing decision in case of 'zone-routing'</td>
</tr>
<tr>
        <th colspan="3">Failure detector configs</th>
</tr>
<tr>
	<td>failuredetector_implementation</td>
	<td>BannagePeriodFailureDetector</td>
	<td>Class name of the failure detector that the client will use. We support BannagePeriodFailureDetector and ThresholdFailureDetector</td>
</tr>
<tr>
	<td>failuredetector_bannage_period</td>
	<td>30000</td>
	<td>BannagePeriodFailureDetector : The number of milliseconds this node is considered as 'banned'</td>
</tr>
<tr>
	<td>failuredetector_threshold_countminimum</td>
	<td>10</td>
	<td>ThresholdFailureDetector : Minimum number of failures that must occur before the success ratio is checked against the threshold</td>
</tr>
<tr>
	<td>failuredetector_threshold_interval</td>
	<td>10000</td>
	<td>ThresholdFailureDetector : Millisecond interval for which the threshold is valid; it is 'reset' after this period is exceeded</td>
</tr>
<tr>
	<td>failuredetector_threshold</td>
	<td>80</td>
	<td>ThresholdFailureDector : The integer percentage representation of the threshold that must be met or exceeded</td>
</tr>
</table>
