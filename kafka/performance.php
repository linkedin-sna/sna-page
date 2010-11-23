<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<body>
	<h2>How to Run a Performance Test</h2>


	<p>The performance related code is under perf folder. To run the simulator :</p>



	<p>&nbsp;../run-simulator.sh -kafkaServer=localhost -numTopic=10&nbsp;  -reportFile=report-html/data -time=15 -numConsumer=20 -numProducer=40  -xaxis=numTopic</p>



	<p>It will run a simulator with 40 producer and 20 consumer threads 
           producing/consuming from a local kafkaserver.&nbsp; The simulator is going to
           run 15 minutes and the results are going to be saved under 
           report-html/data</p>


	<p>and they will be plotted from there. Basically it will write MB of 
           data consumed/produced, number of messages consumed/produced given a 
           number of topic and report.html will plot the charts.</p>


       <p>Other parameters include numParts, fetchSize, messageSize.</p>



       <p>In order to test how the number of topic affects the performance the below script can be used (it is under utl-bin)</p>



       <p>#!/bin/bash<br />
       			 
          for i in 1 10 20 30 40 50;<br />
      
          do<br />
	
          &nbsp; ../kafka-server.sh server.properties 2>&amp;1 >kafka.out&amp;<br />
          sleep 60<br />
	  &nbsp;../run-simulator.sh -kafkaServer=localhost -numTopic=$i&nbsp;  -reportFile=report-html/data -time=15 -numConsumer=20 -numProducer=40  -xaxis=numTopic<br />
          &nbsp;../stop-server.sh<br />
		  &nbsp;rm -rf /tmp/kafka-logs<br />
	     
          &nbsp;sleep 300<br />
	    	   
          done</p>



	<p>The charts similar to below graphs can be plotted with report.html automatically.</p>



	<h2><a name="KafkaPerformanceTestPlan-PerformanceResults"></a>Performance Results</h2>



	<p>We measured</p>

	<ol>
		<li>Messages/sec at broker, consumer and producer</li>
				 <li>MB/sec at broker, consumer, and producer</li>
				 </ol>



				 <p>We&nbsp; varied number of topics,&nbsp; number of consumers, number of 
                                  producers and the data size and do a controlled test to see how each of 
                                 these affect the performance.</p>


      <p>We took below setting for some of the parameters:</p>

      <ul>
	<li>message size = 200 bytes</li>
		    <li>batch size = 200</li>
		    	      <li>fetch size = 1MB</li>
			      		<li>flush interval = 600 messages</li>
					</ul>



					<p>Each performance run is an hour long run. There&nbsp; is 1 broker and several remote consumers and producers.</p>


					<p><span style="font-weight: bold;">Scenario-1:</span></p>



					<p># of partition = 10</p>


					<p># of topics = 10</p>


					<p># of producer =0</p>


					<p># of consumer =10, 20,30, ...</p>


					<p>The below graphs show how the number of consumers affect the total 
data consumed from the server and the data consumed per consumer.</p>









     <p><span style="" class="image-wrap"><img border="0" src="images/perf/onlyconsumerMB.jpg" width="400" height="200"/></span><br />
     	      
<span style="" class="image-wrap"><img border="0" src="images/perf/onlyconsumerNumMsg.jpg" width="400" height="200"/></span><br />
      	       
<span style="font-weight: bold;">Scenario - 2:</span></p>


      <p># of partition = 10</p>


      <p># of consumer = 20</p>


      <p># of producer = 40</p>


      <p># of topis 1 .... 50</p>


      <p>The below graphs show how the number of topics affect the total data 
 consumed/produced from the server and the data consumed/produced per 
consumer/producer.</p>


	<p>&nbsp; <span style="" class="image-wrap"><img border="0" src="images/perf/MBvsnumtopic.jpg" width="400" height="200" /></span></p>


	<p> <span style="" class="image-wrap"><img border="0" src="images/perf/nummessvsnumtopic.jpg" width="400" height="200" /> </span></p>



	<p><span style="font-weight: bold;">Scenario - 3</span></p>


	<p># of partition = 10</p>


	<p># of consumer = 20</p>


	<p># of producer =1 ,10,....</p>


	<p># of topis = 10</p>


	<p>The below graphs show how the number of producers affect the total 
data  consumed/produced from the server and the data consumed/produced 
per consumer.</p>



    <p>&nbsp; <span style="" class="image-wrap"><img border="0" src="images/perf/MBvsnumberofproducers-10-topic.jpg" width="400" height="200" /></span></p>


    <p><span style="" class="image-wrap"><img border="0" src="images/perf/messagevsnumprod-10-topic.jpg" width="400" height="200" /></span></p
	
<?php require "../includes/footer.php" ?>