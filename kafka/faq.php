<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Frequently asked questions</h3>
<ol>	
<li> <h3> Why does my consumer get InvalidMessageSizeException? </h3>
This typically means that the "fetch size" of the consumer is too small. Each time the consumer pulls data from the broker, it reads bytes up to a configured limit. If that limit is smaller than the largest single message stored in Kafka, the consumer can't decode the message properly and will throw an InvalidMessageSizeException. To fix this, increase the limit by setting the property "fetch.size" properly in config/consumer.properties. The default fetch.size is 300,000 bytes.

<li> <h3> On EC2, why can't my high-level consumers connect to the brokers? </h3>
When a broker starts up, it registers its host ip in ZK. The high-level consumer later uses the registered host ip to establish the socket connection to the broker. By default, the registered ip is given by InetAddress.getLocalHost.getHostAddress. Typically, this should return the real ip of the host. However, in EC2, the returned ip is an internal one and can't be connected to from outside. The solution is to explicitly set the host ip to be registered in ZK by setting the "hostname" property in server.properties.

</ol>
