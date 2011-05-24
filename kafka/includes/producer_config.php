<table class="data-table">
<tr>
        <th>property</th>
        <th>default</th>
        <th>description</th>
</tr>
<tr>
    <td><code>serializer.class</code></td>
    <td>kafka.serializer.DefaultEncoder. This is a no-op encoder. The serialization of data to Message should be handled outside the Producer</td>
    <td>class that implements the <code>kafka.serializer.Encoder&lt;T&gt;</code> interface, used to encode data of type T into a Kafka message </td>
</tr>
<tr>
    <td><code>partitioner.class</code></td>
    <td><code>kafka.producer.DefaultPartitioner&lt;T&gt;</code> - uses the partitioning strategy <code>hash(key)%num_partitions</code>. If key is null, then it picks a random partition. </td>
    <td>class that implements the <code>kafka.producer.Partitioner&lt;K&gt;</code>, used to supply a custom partitioning strategy on the message key (of type K) that is specified through the <code>ProducerData&lt;K, T&gt;</code> object in the <code>kafka.producer.Producer&lt;T&gt;</code> send API</td>
</tr>
<tr>
    <td><code>producer.type</code></td>
    <td>sync</td>
    <td>this parameter specifies whether the messages are sent asynchronously or not. Valid values are - <ul><li><code>async</code> for asynchronous batching send through <code>kafka.producer.AyncProducer</code></li><li><code>sync</code> for synchronous send through <code>kafka.producer.SyncProducer</code></li></ul></td>
</tr>
<tr>
    <td><code>broker.list</code></td>
    <td>null. Either this parameter or zk.connect needs to be specified by the user.</td>
    <td>For bypassing zookeeper based auto partition discovery, use this config to pass in static broker and per-broker partition information. Format-<code>brokerid1:host1:port1, brokerid2:host2:port2.</code>
	If you use this option, the <code>partitioner.class</code> will be ignored and each producer request will be routed to a random broker partition.</td>
</tr>
<tr>
    <td><code>zk.connect</code></td>
    <td>null. Either this parameter or broker.partition.info needs to be specified by the user</td>
    <td>For using the zookeeper based automatic broker discovery, use this config to pass in the zookeeper connection url to the zookeeper cluster where the Kafka brokers are registered.</td>
</tr>
<tr><td></td><td><b>The following config parameters are related to</b> <code><b>kafka.producer.async.AsyncSyncProducer</b></code></td><tr>
<tr>
    <td><code>queue.time</code></td>
    <td>5000</td>
    <td>maximum time, in milliseconds, for buffering data on the producer queue. After it elapses, the buffered data in the producer queue is dispatched to the <code>event.handler</code>.</td>
</tr>
<tr>
    <td><code>queue.size</code></td>
    <td>10000</td>
    <td>the maximum size of the blocking queue for buffering on the <code> kafka.producer.AsyncProducer</code></td>
</tr>
<tr>
    <td><code>batch.size</code> </td>
    <td>200</td>
    <td>the number of messages batched at the producer, before being dispatched to the <code>event.handler</code></td>
</tr>
<tr>
    <td><code>event.handler</code></td>
    <td><code>kafka.producer.async.EventHandler&lt;T&gt;</code></td>
    <td>the class that implements <code>kafka.producer.async.IEventHandler&lt;T&gt;</code> used to dispatch a batch of produce requests, using an instance of <code>kafka.producer.SyncProducer</code>. 
</td>
</tr>
<tr>
    <td><code>event.handler.props</code></td>
    <td>null</td>
    <td>the <code>java.util.Properties()</code> object used to initialize the custom <code>event.handler</code> through its <code>init()</code> API</td>
</tr>
<tr>
    <td><code>callback.handler</code></td>
    <td><code>null</code></td>
    <td>the class that implements <code>kafka.producer.async.CallbackHandler&lt;T&gt;</code> used to inject callbacks at various stages of the <code>kafka.producer.AsyncProducer</code> pipeline.
</td>
</tr>
<tr>
    <td><code>callback.handler.props</code></td>
    <td>null</td>
    <td>the <code>java.util.Properties()</code> object used to initialize the custom <code>callback.handler</code> through its <code>init()</code> API</td>
</tr>
<tr><td></td><td><b>The following config parameters are related to</b> <code><b>kafka.producer.SyncProducer</b></code></td><tr>
<tr>
    <td><code>buffer.size</code></td>
    <td>102400</td>
    <td>the socket buffer size, in bytes</td>
</tr>
<tr>
    <td><code>connect.timeout.ms</code></td>
    <td>5000</td>
    <td>the maximum time spent by <code>kafka.producer.SyncProducer</code> trying to connect to the kafka broker. Once it elapses, the producer throws an ERROR and stops.</td>
</tr>
<tr>
    <td><code>socket.timeout.ms</code></td>
    <td>30000</td>
    <td>The socket timeout in milliseconds</td>
</tr>
<tr>
    <td><code>reconnect.interval</code> </td>
    <td>30000</td>
    <td>the number of produce requests after which <code>kafka.producer.SyncProducer</code> tears down the socket connection to the broker and establishes it again</td>
</tr>
<tr>
    <td><code>max.message.size</code> </td>
    <td>1000000</td>
    <td>the maximum number of bytes that the kafka.producer.SyncProducer can send as a single message payload</td>
</tr></table>
