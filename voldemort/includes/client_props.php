<table class="data-table">
<tr>
	<th>name</th>
	<th>default</th>
	<th>description</th>
</tr>
<tr>
	<td>max_connections</td>
    <td>6</td>
    <td>Maximum number of connection allowed to each voldemort node</td>
</tr>
<tr>
	<td>max_total_connections</td>
	<td>500</td>
	<td>Maximum number of connection allowed to all voldemort node</td>
</tr>
<tr>
	<td>max_threads</td>
	<td>5</td>
	<td>The maximum number of client threads </td>
</tr>
<tr>
	<td>max_queued_requests</td>
	<td>50</td>
	<td>The maximum number of queued node operations before client actions will be blocked</td>
</tr>
<tr>
	<td>thread_idle_ms</td>
	<td>100000</td>
	<td>The amount of time to keep an idle client thread alive</td>
</tr>
<tr>
	<td>connection_timeout_ms</td>
	<td>500</td>
	<td>Set the maximum allowable time to block waiting for a free connection</td>
</tr>
<tr>
	<td>socket_timeout_ms</td>
	<td>500</td>
	<td>Maximum amount of time the socket will block waiting for network activity</td>
</tr>
<tr>
	<td>routing_timeout_ms</td>
	<td>15000</td>
	<td>Set the timeout for all blocking operations to complete on all nodes. The number of blocking operations can be configured using the preferred-reads and preferred-writes configuration for the store.</td>
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
</table>
