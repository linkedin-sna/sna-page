<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Kafka is a distributed publish/subscribe messaging system</h2>
<p>
Kafka is a distributed publish-subscribe messaging system. It is designed to support the following
<ul>
	<li>Persistent messaging with O(1) disk structures that provide constant time performance even with many TB of stored messages.</li>
	<li>High-throughput: even with very modest hardware Kafka can support hundreds of thousands of messages per second.</li>
    <li>Explicit support for partitioning messages over Kafka servers and distributing consumption over a cluster of consumer machines while maintaining per-partition ordering semantics.</li>
    <li>Support for parallel data load into Hadoop.</li>
</ul>

Kafka is aimed at providing a publish-subscribe solution that can handle all activity stream data and processing on a consumer-scale web site. This kind of activity (page views, searches, and other user actions) are a key ingredient in many of the social feature on the modern web. This data is typically handled by "logging" and ad hoc log aggregation solutions due to the throughput requirements. This kind of ad hoc solution is a viable solution to providing logging data to an offline analysis system like Hadoop, but is very limiting for building real-time processing. Kafka aims to unify offline and online processing by providing a mechanism for parallel load into Hadoop as well as the ability to partition real-time consumption over a cluster of machines.
</p>

<p>
See our <a href="design.php">design</a> page for more details.	
</p>

<p>
This is a new project, and we are interested in building the community; we would welcome any thoughts or patches. You can reach us <a href="http://groups.google.com/group/kafka-dev">here<a/>.

<?php require "../includes/footer.php" ?>