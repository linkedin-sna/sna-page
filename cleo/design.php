<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Design
</h1>

<p>
Cleo is designed to support typeahead search for members' social network connections and activities.
Cleo relies on
<a href="http://en.wikipedia.org/wiki/Adjacency_list">Adjacency List</a>,
<a href="http://en.wikipedia.org/wiki/Bloom_filter">Bloom Filter</a> and
<a href="http://en.wikipedia.org/wiki/Forward_index#The_Forward_Index">Forward Index</a>
to enable partial out-of-order string prefix match over a vary large number of elements by means of in-memory scanning and filtering.
The design of Cleo can be summarized as:
</p>

<ul>
  <li><b>Adjacency List</b> is for storing members' network connections. </li>
  <li><b>Bloom Filter</b> provides a fast means to filter prefix mismatches. </li>
  <li><b>Forward Index</b> is used to reject false positives from filtering. </li>
</ul>

<p>
During indexing, a tiny bloom filter (e.g., 4 bytes) is calculated against the pre-processed terms (e.g., <code>{"jeff", "weiner"}</code>) of an element using a predefined hash function <i>h</i>.
During search, the same function <i>h</i> is used to calculate the query bloom filter against the terms in a typeahead query (e.g., <code>{"j", "wein"}</code>).
The connections of the searcher are retrieved in the form of adjacency list in memory.
The connections are scanned to filter out prefix mismatch using the query bloom filter and the pre-computed bloom filter values for every member connected to the searcher.
Upon each bloom filter hit, the forward index is visited to reject any potential false positive prefix matches.
</p>

<p>
The design simplicity of Cleo makes it easy to combine and/or extend the three key components for varying application purposes.
The Cleo library has provided several different typeahead search implementations which we will discuss shortly.
</p>

<h3>
Basic API
</h3>

<p>
Similar to general-purpose search libraries, Cleo provides separate interfaces for searching and indexing.
The following classes define the basic API of Cleo.
</p>

<ul>
  <li><strong>Element</strong></li>
  <p>
  An <code>Element</code> represents an entity that is characterized by a set of terms (words) and
  is retrievable by the prefixes of each known term. The terms of an element may be pre-processed
  (e.g., lowercase) depending on application needs. Elements are retrieved and included in
  typeahead search results.
  </p>

  <li><strong>Searcher</strong></li>
  <p>
  The <code>Searcher</code> API defines a number of methods for retrieving elements (i.e., search results) from
  the underlying indexes that are populated by an <code>Indexer</code> in real-time. 
  </p>

  <li><strong>Indexer</strong></li>
  <p>
  The <code>Indexer</code> is for indexing various types of elements. In general, elements need to be serializable
  so that they can be stored persistently.
  </p>

  <li><strong>Connection</strong></li>
  <p>
  The <code>Connection</code> is for representing different social relationships (e.g., friends, network connections, group associations).
  </p>
  
  <li><strong>ConnectionIndexer</strong></li>
  <p>
  The <code>ConnectionIndexer</code> is for indexing various types of connections.
  </p>
</ul>

<h3>
Core Typeahead Classes
</h3>

<p>
There are different implementations for typeahead search in Cleo.
The core <code>Typeahead</code> classes listed below all implement the <code>Searcher</code> and <code>Indexer</code> interfaces.
Generic typeahead search is typically implemented as the subclasses of <code>AbstractTypeahead</code>.
In contrast, <code>NetworkTypeahead</code> need to maintain various social network connections to support typeahead search over a person's social network.
Therefore, the subclasses of <code>NetworkTypeahead</code> implement the <code>ConnectionIndexer</code> interface.
The following figure shows the class inheritance hierarchy of <code>Searcher</code>.
</p>

<p align="center">
<img src="images/cleo-api-searcher.png" alt="Cleo Searcher API" width="100%"/>
</p>
  
<ul>
  <li><strong>GenericTypeahead</strong></li>
  <p>
  The <code>GenericTypeahead</code> is designed for large data sets, which may contain millions of elements,
  in the absence of social network connections. It is built on <code>Inverted Index</code>
  (similar to the adjacency list in the sense that a term is associated with a list of element IDs),
  <code>Bloom Filter</code> and <code>Forward Index</code>.
  </p>
  
  <p>
  When evaluating a query, the typeahead selects the shortest list associated with the prefixes of query terms
  from the inverted index. The selected list is scanned. The <code>GenericTypeahead</code> applies bloom filter to reject prefix mismatches, and
  visits the forward index to reject false positive matches left by the bloom filter.
  The typeahead search results are collected and then ranked according to their popularity
  such as the number of clicks or page views.
  </p>
  
  <li><strong>ScannerTypeahead</strong></li>
  <p>
  The <code>ScannerTypeahead</code> is suitable for small data sets that contain no more than 500,000 elements.
  It uses <code>Bloom Filter</code> and <code>Forward Index</code> but not <code>Adjacency List</code>.
  When evaluating a query, the <code>ScannerTypeahead</code> scans all the elements,
  applies bloom filter to reject prefix mismatches, and
  visits the forward index to reject false positive matches left by the bloom filter.
  </p>
  
  <li><strong>VanillaNetworkTypeahead</strong></li>
  <p>
  The <code>VanillaNetworkTypeahead</code> provides a basic implementation for Network Typeahead using the three key techniques
  <code>Adjacency List</code>, <code>Bloom Filter</code> and <code>Forward Index</code> in the presence of social network
  such as professional network connections, friendship and followership. A person's network is
  represented as an adjacency list (i.e. a mapping from a source ID to a list of target IDs).
  There is no further information about how strong a relationship between two members can be.
  So the concept of closeness is not available.
  </p>
  <p>
  When evaluating a query from a searcher, the <code>VanillaNetworkTypeahead</code> retrieves and scans the searcher's
  social network connections from the adjacency list, applies bloom filter to reject prefix mismatches, and
  visits the forward index to reject false positive matches left by the bloom filter.
  </p>
  
  <li><strong>WeightedNetworkTypeahead</strong></li>
  <p>
  The <code>WeightedNetworkTypeahead</code> improves upon <code>VanillaNetworkTypeahead</code> by
  extending <code>Adjacency List</code> with a integer weight defined for each social relationship in the list.
  The closeness of a social relationship can be modeled and then used to rank typeahead search results for better
  relevance. In fact, the LinkedIn 1st and 2nd degree network connections typeahead leverages the connection scores
  from the PYMK (People You May Know) to make typeahead search results relevant. 
  </p>
  
  <p>
  When evaluating a query from a searcher, the <code>VanillaNetworkTypeahead</code> retrieves and scans the searcher's
  social network connections from the adjacency list, applies bloom filter to reject prefix mismatches, and
  visits the forward index to reject false positive matches left by the bloom filter. The typeahead search results
  are collected and then ranked based on their corresponding connection scores from the adjacency list.
  </p>
  
</ul>


<?php require "../includes/footer.php" ?>
