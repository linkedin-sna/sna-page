<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<body>



	<h2><a name="KafkaPerformanceTestPlan-PerformanceResults"></a>Performance Results</h2>
	<p>We measured</p>

	<ol>
		<li>Messages/sec at consumer and producer</li>
				 <li>MB/sec at consumer, and producer</li>
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



					<p>There&nbsp; is 1 broker and several remote consumers and producers.</p>





        In our performance tests, we run experiments to answer below questions.
        <h3>How much data can we push and what is the effect of batch size? </h3>
		<p>We can push about 50MB/sec to the system. However, this number changes with the batch size. The below graphs show the relation between these two.<p>
		 <p><span style="" class="image-wrap"><img border="0" src="images/onlyBatchSize.jpg" width="500" height="300"/></span><br /></p>


        <h3>How much data can we consume? And how the number of consumers affect that?</h3>
         <p>According to our experiments, we can consume about 100M/sec from a broker and the total does not seem to change as we increase
		  the number of consumers (however, data consumed per consumer decreases linearly as tota consumed data does not change)<p>
		<p>The below graphs show how the number of consumers affect the total data consumed from the server and the data consumed per consumer.</p>
        <p><span style="" class="image-wrap"><img border="0" src="images/onlyConsumer.jpg" width="500" height="300"/></span> </p>



        <h3> Does data size effect our performance numbers? </h3>
        <p> This does not have any affect on the performance based on our experiements. The below graph shows the number of messages produced with respect to data size.<p>
     	<p><span style="" class="image-wrap"><img border="0" src="images/dataSize.jpg" width="500" height="300"/></span><br /></p>
        <h3> What is the effect of number of producers on the size of data produced? </h3>
         <p> Based on our experiments, the number of producer has a minimal effect on the total data produced <p>
     	<p><span style="" class="image-wrap"><img border="0" src="images/onlyProducer.jpg" width="500" height="300"/></span><br /></p>

        <h3> What is the effect of number of topics on the size of data produced? </h3>
         <p> Based on our experiments, the number of topic has a minimal effect on the total data produced 
             The below graph is an experiment where we used 40 producers and varied the number of topics<p>

        <p><span style="" class="image-wrap"><img border="0" src="images/onlyTopic.jpg" width="500" height="300"/></span><br /></p>


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



	<p>The charts similar to above graphs can be plotted with report.html automatically.</p>

<?php require "../includes/footer.php" ?>
