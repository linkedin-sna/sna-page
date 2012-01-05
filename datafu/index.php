<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<p>
DataFu is a collection of user-defined functions for working with
large-scale data in Hadoop and Pig. This library was born out of the
need for a stable, well-tested library of UDFs for data mining and
statistics. It is used at LinkedIn in many of our off-line workflows
for data derived products like "People You May Know" and "Skills". It
contains functions for:
</p>

<ul>
    <li>PageRank</li>
    <li>Quantiles (median), variance, etc.</li>
    <li>Sessionization</li>
    <li>Convenience bag functions (e.g., set operations, enumerating bags, etc)</li>
    <li>Convenience utility functions (e.g., assertions, easier writing of</li>
    EvalFuncs)
    <li>and <a href="javadoc/0.0.1">more</a>...</li>
</ul>

<p>
Each function is unit tested and code coverage is being tracked for
the entire library. We are actively adding new functions and welcome 
contributions. The source code is available under the Apache 2.0 license. 
</p>

<h2>What can you do with it?</h2>

Here's a taste of what you can do in Pig.

<h3>Statistics</h3>

<p>
Compute the <a href="http://en.wikipedia.org/wiki/Median">median</a> of a sequence of sorted bags:
</p>

<pre>
  define Median datafu.pig.stats.Median();
  
  -- input: 3,5,4,1,2
  input = LOAD 'input' AS (val:int);
    
  grouped = GROUP input ALL;
  
  -- produces median of 3
  medians = FOREACH grouped {
    sorted = ORDER input BY val;
    GENERATE Median(sorted);
  }
</pre>

<p>
Similarly, compute any arbitrary <a href="http://en.wikipedia.org/wiki/Quantile">quantiles</a>:
</p>

<pre>
  define Quantile datafu.pig.stats.Quantile('0.0','0.5','1.0');
  
  -- input: 9,10,2,3,5,8,1,4,6,7
  input = LOAD 'input' AS (val:int);
  
  grouped = GROUP input ALL;
  
  -- produces: (1,5.5,10)
  quantiles = FOREACH grouped {
    sorted = ORDER input BY val;
    GENERATE Quantile(sorted);
  }
</pre>

<h3>Set Operations</h3>

<p>
Treat sorted bags as sets and compute their intersection:
</p>

<pre>
  define SetIntersect datafu.pig.bags.sets.SetIntersect();
  
  -- ({(3),(4),(1),(2),(7),(5),(6)},{(0),(5),(10),(1),(4)})
  input = LOAD 'input' AS (B1:bag{T:tuple(val:int)},B2:bag{T:tuple(val:int)});

  -- ({(1),(4),(5)})
  intersected = FOREACH input {
    sorted_b1 = ORDER B1 by val;
    sorted_b2 = ORDER B2 by val;
    GENERATE SetIntersect(sorted_b1,sorted_b2);
  }
</pre>

<p>
Compute the set union:

<pre>
  define SetUnion datafu.pig.bags.sets.SetUnion();
  
  -- ({(3),(4),(1),(2),(7),(5),(6)},{(0),(5),(10),(1),(4)})
  input = LOAD 'input' AS (B1:bag{T:tuple(val:int)},B2:bag{T:tuple(val:int)});

  -- ({(3),(4),(1),(2),(7),(5),(6),(0),(10)})
  unioned = FOREACH input GENERATE SetUnion(B1,B2);
</pre>

Operate on several bags even:

<pre>  
  unioned = FOREACH input GENERATE SetUnion(B1,B2,B3);
</pre>
  
<h3>Bag operations</h3>

Concatenate two or more bags:

<pre>
  define BagConcat datafu.pig.bags.BagConcat();

  -- ({(1),(2),(3)},{(4),(5)},{(6),(7)})
  input = LOAD 'input' AS (B1: bag{T: tuple(v:INT)}, B2: bag{T: tuple(v:INT)}, B3: bag{T: tuple(v:INT)});

  -- ({(1),(2),(3),(4),(5),(6),(7)})
  output = FOREACH input GENERATE BagConcat(B1,B2,B3);
</pre>

Append a tuple to a bag:

<pre>
  define AppendToBag datafu.pig.bags.AppendToBag();
  
  -- ({(1),(2),(3)},(4))
  input = LOAD 'input' AS (B: bag{T: tuple(v:INT)}, T: tuple(v:INT));

  -- ({(1),(2),(3),(4)})
  output = FOREACH input GENERATE AppendToBag(B,T);
</pre>

<h3>PageRank</h3>

<p>
Run <a href="http://en.wikipedia.org/wiki/PageRank">PageRank</a> on a large number of independent graphs.  
</p>

<pre>
  define PageRank datafu.pig.linkanalysis.PageRank('dangling_nodes','true');
  
  topic_edges = LOAD 'input_edges' as (topic:INT,source:INT,dest:INT,weight:DOUBLE);
  
  topic_edges_grouped = GROUP topic_edges by (topic, source);
  topic_edges_grouped = FOREACH topic_edges_grouped GENERATE
    group.topic as topic,
    group.source as source,
    topic_edges.(dest,weight) as edges;
  
  topic_edges_grouped_by_topic = GROUP topic_edges_grouped BY topic; 
  
  topic_ranks = FOREACH topic_edges_grouped_by_topic GENERATE
    group as topic,
    FLATTEN(PageRank(topic_edges_grouped.(source,edges))) as (source,rank);
  
  skill_ranks = FOREACH skill_ranks GENERATE
    topic, source, rank;
</pre>

<p>
This implementation stores the nodes and edges (mostly) in memory.  It is therefore best suited when one needs to compute PageRank on many reasonably sized graphs in parallel.
</p>

<p>
See the sample <a href="examples/pagerank_input.txt">input</a> and <a href="examples/pagerank_output.txt">output</a> for the example graph shown
<a href="http://en.wikipedia.org/wiki/PageRank">here</a>.  There is only a single graph in this case.  Nodes A-F were given IDs 1-6, the remainder 100+.
<p>

<h2>Getting started</h2>

<p>
Fetch from <a href="https://github.com/linkedin/datafu">here</a>.
</p>

<p>
To build the JAR: ant jar<br>
To run all tests: ant test<br>
To build the javadocs: ant docs<br>
</p>

<h2>Contribute</h2>

<p>
We're trying to grow the library and are actively looking for contributors.  Check out the code and send us your pull requests!  Please also include unit tests.
</p>

<?php require "../includes/footer.php" ?>
