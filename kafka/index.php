<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Kafka is a distributed publish/subscribe messaging system</h2>
<p>
Kafka is a distributed publish/subscribe messaging system. It is designed to support the following
<ul>
	<li>Persistent messaging with O(1) disk structures that provide constant time performance up to many TB of stored messages.</li>
	<li>High-throughput--even with very modest hardware kafka can support hundreds of thousands of messages per second.</li>
    <li>Explicit support for partitioning messages over kafka servers and distributing consumption over multiple consumer machines while maintaining per-partition ordering.</li>
    <li>Support for parallel data load into Hadoop.</li>
</ul>
</p>

<h2>Activity stream processing</h2>
<p>
Kafka is aimed at providing a publish/subscribe solution that can handle all activity on a consumer-scale web site. This kind of activity (page views, searches, and other user actions) are a key ingredient in many of the social feature on the modern web. This data is typically handled by "logging" and ad hoc log aggregation solutions due to the throughput requirements. This kind of ad hoc solution is a viable solution to providing logging data to an offline analysis system like Hadoop, but is very limiting for building real-time processing. Kafka aims to unify offline and online processing by providing a mechanism for parallel load into Hadoop as well as the ability to partition real-time consumption over a cluster of machines.
</p>

<?php require "../includes/footer.php" ?>