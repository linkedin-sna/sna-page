<table class="data-table">
<tr>
        <th>name</th>
        <th>default</th>
        <th>description</th>
</tr>
<tr>
    <td><code>groupid</code></td>
    <td>none</td>
    <td>is a string that uniquely identifies a set of consumers within the same consumer group. </td>
</tr>
<tr>
    <td><code>autocommit.enable</code></td>
    <td></td>
    <td>if set to true, the consumer periodically commits to zookeeper the latest consumed offset of each partition. </td>
</tr>
<tr>
    <td><code>autocommit.interval.ms</code> </td>
    <td></td>
    <td>is the frequency that the consumed offsets are committed to zookeeper. </td>
</tr>
<tr>
    <td><code>autooffset.reset</code></td>
    <td></td>
    <td><ul>
 <li> <code>smallest</code>: automatically reset the offset to the smallest offset available on the broker.</li>
 <li> <code>largest</code> : automatically reset the offset to the largest offset available on the broker.</li>
 <li> <code>anything else</code>: throw an exception to the consumer.</li>
</ul>
</td>
</tr>
<tr>
    <td><code>consumer.timeout.ms</code></td>
    <td></td>
    <td>By default, this value is -1 and a consumer blocks indefinitely if no new message is available for consumption. By setting the value to a positive integer, a timeout exception is thrown to the consumer if no message is available for consumption after the specified timeout value.</td>
</tr>
</table>
