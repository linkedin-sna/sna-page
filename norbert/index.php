<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<p>
Norbert is a library that provides easy cluster management and workload distribution. With Norbert, you can quickly distribute a simple client/server architecture to create a highly scalable architecture capable of handling heavy traffic. 
</p>
<p>
Implemented in Scala, Norbert wraps   <a href="http://hadoop.apache.org/zookeeper/">ZooKeeper</a> and <a href="http://jboss.org/netty">Netty</a>
	and uses <a href="http://code.google.com/p/protobuf/">Protocol Buffers</a> for transport to make it easy to build a cluster aware application.  A Java API is provided and pluggable routing strategies are supported with a consistent hash strategy provided out of the box.
</p>

<p>
Norbert was designed to addresses the following challenges and hide the complexity and details from the users.
<ul>
  <li> Providing group management. Norbert makes it easy to add or remove service nodes or to change configurations in the cluster.
  <li> Using software load balancing to partition workload.
  <li> Providing asynchronies client/server RPC and notifications
</ul>
</p>

<p>
Norbert uses zookeeper for the underlying group management. Both the client and server part of Norbert communicate with zookeeper to track changes in the cluster configuration. Zookeeper ensures Norbert always has accurate and consistent information about the cluster, freeing users from having to worry about cluster management details. 
</p>

<p>
Before turning a centralized service into a clustered service, you must decide how to distribute the work.  First, you should choose a partition space, e.g. member Id, to split the workload across. Next, you can either choose to use a provided load balancing strategy or write your own. Norbert currently uses Protocol Buffers as the serialization strategy within the system and for RPC with your application. Netty is used to provide faster NIO.
</p>

<p>
  Sample code for a Java based client and server can be found <a href="http://github.com/rhavyn/norbert/blob/master/java-network/src/main/java/com/linkedin/norbert/network/javaapi/JavaNetworkClientMain.java">here</a>
  and <a href="http://github.com/rhavyn/norbert/blob/master/java-network/src/main/java/com/linkedin/norbert/network/javaapi/JavaNetworkServerMain.java">here</a>.
</p>
<p>
<img src="images/norbert_architecture.jpg"/>
</p>


<?php require "../includes/footer.php" ?>