<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<p>
<h1>
Distributed elastic realtime searchable database
</h1>
</p>

<p>
Sensei is a distributed database that is designed to handle the following type of query:
<br/>
<br/>
<b>
SELECT f1,f2...fn FROM members <br/>
WHERE c1 AND c2 AND c3..<br/>
MATCH (fulltext query, e.g. "java engineer")<br/>
GROUP BY fx,fy,fz...<br/>
ORDER BY fa,fb...<br/>
LIMIT offset,count<br/>
</b>
</p>

<p>
<h2>
Design considerations:
</h2>
</p>
<ul>
    <li> data:
       <ul>
          <li> Fault tolerance - when one replication is down, data is still accessible</li>
          <li> Durability - N copies of data is stored </li>
          <li> Through-put - Parallelizable request-handling on different nodes/data replicas, designed to handle internet traffic </li>
          <li> Consistency - Eventally consistent</li>
          <li> Data recovery - each shared/replica is noted with a watermark for data recovery </li>
          <li> Large dataset - designed to handle 100s millions - billions of rows </li>
       </ul>
    </li>
    <li> horizontally scalable:
      <ul>
         <li> Data is partitioned - so work-load is also partitioned </li>
         <li> Elasticity - Nodes can be added to accomodate data growth </li>
         <li> Online expansion - Cluster can grow while handling online requests </li>
         <li> Online cluster management - Cluster topology can change while handling online requests </li>
         <li> Low operational/maintenance costs - Push it, leave it and forget it. </li>
      </ul>
    </li>
    <li> performance:
      <ul>
         <li> low indexing latency - real-time update </li>
         <li> low search latency - millisecond query response time </li>
         <li> low volatility - low variance in both indexing and search latency </li>
      </ul>
    </li>
    <li> customizability:
      <ul>
         <li> plug-in framework - custom query handling logic </li>
         <li> routing factory - custom routing logic, default: round-robin </li>
         <li> index sharding strategy - different sharding strategy for different applications, e.g. time, mod etc. </li>
      </ul>
    </li>
</ul>
<p>
<h2>
Comparing to traditional RDBMS:
</h2>
<b>RDBMS:</b>
<ul>
<li>vertically scaled</li>
<li>strong ACID guarantee</li>
<li>relational support</li>
<li>performance cost with full-text integration</li>
<li>high query latency with large dataset, esp. Group By</li>
<li>indexes needs to be built for all sort possibilities offline</li>
</ul>
<b>Sensei:</b>
<ul>
<li> horizontally scaled</li>
<li> relaxed Consistency with high durability guarantees</li>
<li> data is streamed in, so Atomicity and Isolation is to be handled by the data producer</li>
<li> full-text support </li>
<li> low query latency with arbitrarily large dataset</li>
<li> dynamic sorting, index is already built for all sortable fields and their combinations</li>
</ul>
</p>
<p>
<h2>
Architectural Diagram:
</h2>
</p>
<p align="center">
  <img src = "images/sensei-overview.jpg" />
</p>

<?php require "../includes/footer.php" ?>
