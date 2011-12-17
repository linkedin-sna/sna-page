<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Cleo
</h1>

<p>
<strong>Cleo</strong> is a flexible software library for enabling rapid development of partial,
out-of-order and real-time typeahead and autocomplete services. It is suitable for data sets of various sizes from
different domains. The Cleo software library is published under the terms of the Apache Software
License version 2.0, a copy of which has been included in the LICENSE file shipped with the Cleo
distribution.
</p>

<p>
Not to be mistaken with query autocomplete, Cleo does not suggest search terms or queries.
Cleo is a library for developing applications that can perform real typeahead queries and deliver
instantaneous typeahead results/objects/elements as you type.
</p>

<p>
Cleo is also different from general-purpose search libraries because
<b>1)</b> it does not evaluate search terms but the prefixes of those terms, and
<b>2)</b> it enables search by means of Bloom Filter and forward indexes rather than inverted indexes.
</p>

<p>
Still confused about the meanings of Cleo? Let's now take a look at a search query example.
If you perform a search query, say "j weiner", at Google, you will have a list of suggested search queries as you type.
This list changes automatically depending on the words in your search query. This is query autocomplete.
You choose a search query from the list and then Google provides you with corresponding search results. 
</p>
<p align="center">
  <img src="images/google-typeahead.png" alt="Google search autocomplete for j wein" width="100%"/>
</p>
<p>
On LinkedIn, when you type "j wein", you are presented a list of search results instead of search queries.
These search results are real-time, aggregated from different search domains, and then filtered accordingly
based on your 1st and 2nd degree network connections. By real-time, we mean that any new members joining LinkedIn
are immediately searchable through Cleo-powered typeahead services.
<p>
<p align="center">
  <img src="images/linkedin-typeahead.png" alt="LinkedIn typeahead search for j wein"/>
</p>

<h3>
Cleo @ LinkedIn
</h3>

<p>
Cleo has been in extensive use to power LinkedIn real-time typeahead search covering different data sets, which include members (1st and 2nd degree network connections),
companies, groups, questions, skills, and various site features. Its use cases are in two broad categories:
</p>

<ul>
  <li><strong>Generic Typeahead</strong></li>
  <p>
  The same typeahead query from different members produces the same search results that can be ordered based on a global ranking scheme (e.g., popularity).
  A member's social network has no impact on the search results.
  </p>

  <li><strong>Network Typeahead</strong></li>
  <p>
  The same query from different members produces different search results that are filtered according to a member's social network (1st and 2nd degree network connections).
  The current typeahead for LinkedIn members' network connections leverages the LinkedIn PYMK (People You May Know) scores to rank search results for better relevance.
  </p>
</ul>

<p>
From an architectural perspective, LinkedIn typeahead search is composed of different layers: browser cache, web tier, results aggregator, and various typeahead backend services.
Cleo is powering all backend services. Each backend service runs in a cluster and presents the same API to the aggregator.
Depending on the landing page, the aggregator automatically choose and aggregate different types of typeahead search results for the web tier to consume.
The browser cache is also used to cache typeahead search results for faster rendering. 
</p>

<p align="center">
  <img src="images/linkedin-typeahead-search-architecture.png" alt="LinkedIn typeahead search architecture" width="66%"/>
</p>

<p>
From a performance perspective, Cleo is very fast in returning typeahead search results.
Within a cluster, generic typeahead services can normally return results in less than 1 millisecond.
In contrast, network typeahead services are slower when the total number of 1st and 2nd degree network connections is very large.
For performance reasons, a timeout is set at 15 milliseconds and it may occur for LinkedIn members such as recruiters and <a href="http://en.wikipedia.org/wiki/LinkedIn_Open_Networker">LION</a> (LinkedIn Open Networker), who typically have a very large network.
At the level of aggregator, the response time is approximately 20 to 25 milliseconds on average.
</p>

<h3>
References
</h3>

<ol>
  <li><a href="http://www.facebook.com/note.php?note_id=389105248919">The Life of a Typeahead Query</a></li>
  <p>
  This is an excellent Facebook engineering blog on understanding the various technical aspects and challenges of real-time typeahead search
  in the context of social network.
  <p>
  
  <li><a href="http://dl.acm.org/citation.cfm?id=1559918">Efficient type-ahead search on relational data: a TASTIER approach</a></li>
  <p>
  This research paper describes a relational approach to typeahead search by means of specialized index structures and algorithms for joining
  related tuples in the database.
  </p>
</ol>

<?php require "../includes/footer.php" ?>
