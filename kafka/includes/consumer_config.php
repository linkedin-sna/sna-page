<table class="data-table">
<tr>
        <th>property</th>
        <th>default</th>
        <th>description</th>
</tr>
<tr>
    <td><code>groupid</code></td>
    <td>groupid</td>
    <td>is a string that uniquely identifies a set of consumers within the same consumer group. </td>
</tr>
<tr>
    <td><code>socket.timeout.ms</code></td>
    <td>30000</td>
    <td>controls the socket timeout for network requests </td>
</tr>
<tr>
    <td><code>socket.buffersize</code></td>
    <td>64*1024</td>
    <td>controls the socket receive buffer for network requests</td>
</tr>
<tr>
    <td><code>fetch.size</code></td>
    <td>300 * 1024</td>
    <td>controls the number of bytes of messages to attempt to fetch in one request to the Kafka server</td>
</tr>
<tr>
    <td><code>backoff.increment.ms</code></td>
    <td>1000</td>
    <td>This parameter avoids repeatedly polling a broker node which has no new data. We will backoff every time we get an empty set 
from the broker for this time period</td>
</tr>
<tr>
    <td><code>queuedchunks.max</code></td>
    <td>100</td>
    <td>the high level consumer buffers the messages fetched from the server internally in blocking queues. This parameter controls
the size of those queues</td>
</tr>
<tr>
    <td><code>autocommit.enable</code></td>
    <td>true</td>
    <td>if set to true, the consumer periodically commits to zookeeper the latest consumed offset of each partition. </td>
</tr>
<tr>
    <td><code>autocommit.interval.ms</code> </td>
    <td>10000</td>
    <td>is the frequency that the consumed offsets are committed to zookeeper. </td>
</tr>
<tr>
    <td><code>autooffset.reset</code></td>
    <td>smallest</td>
    <td><ul>
 <li> <code>smallest</code>: automatically reset the offset to the smallest offset available on the broker.</li>
 <li> <code>largest</code> : automatically reset the offset to the largest offset available on the broker.</li>
 <li> <code>anything else</code>: throw an exception to the consumer.</li>
</ul>
</td>
</tr>
<tr>
    <td><code>consumer.timeout.ms</code></td>
    <td>-1</td>
    <td>By default, this value is -1 and a consumer blocks indefinitely if no new message is available for consumption. By setting the value to a positive integer, a timeout exception is thrown to the consumer if no message is available for consumption after the specified timeout value.</td>
</tr>
</table>
