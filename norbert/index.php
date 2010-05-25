<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<p>
Norbert is a library which provides easy cluster management and cluster aware client/server networking APIs.  Implemented in Scala, Norbert wraps   <a href="http://hadoop.apache.org/zookeeper/">ZooKeeper</a> and <a href="http://jboss.org/netty">Netty</a>
	and uses <a href="http://code.google.com/p/protobuf/">Protocol Buffers</a> for transport to make it easy to build a cluster aware application.  A Java API is provided and pluggable routing strategies are supported with a consistent hash strategy provided out of the box.
</p>

<p>
  Sample code for a Java based client and server can be found <a href="http://github.com/rhavyn/norbert/blob/master/java-network/src/main/java/com/linkedin/norbert/network/javaapi/JavaNetworkClientMain.java">here</a>
  and <a href="http://github.com/rhavyn/norbert/blob/master/java-network/src/main/java/com/linkedin/norbert/network/javaapi/JavaNetworkServerMain.java">here</a>.
</p>

<?php require "../includes/footer.php" ?>