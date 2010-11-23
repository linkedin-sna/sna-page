<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2> Configuration </h2>

<h3> Important configuration properties for Kafka broker: </h3>

<p>More details about server configuration can be found in the scala class <code>kafka.server.KafkaConfig</code>.</p> 

<?php include('includes/server_config.php'); ?>

<h3> Important configuration properties for the high-level consumer: </h3>

<p>More details about server configuration can be found in the scala class <code>kafka.consumer.ConsumerConfig</code>.</p> 

<?php include('includes/consumer_config.php'); ?>

<?php require "../includes/footer.php" ?>
