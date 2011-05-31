<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Overview:
</h1>
<p>
Zoie is a real-time search and indexing system built on Apache Lucene.
</p>
<p>
Donated by LinkedIn.com on July 19, 2008, and has been deployed in a real-time large-scale consumer website: LinkedIn.com handling millions of searches as well as millions of updates daily.
</p>

<p>
<i>
News: Zoie 2.6.0 is released (<a href="http://github.com/javasoze/zoie/downloads">here</a>) - Compatible with Lucene 2.9.x.
</i>
</p>

<p>
In a real-time search/indexing system, a document is made available as soon as it is added to the index. 
This functionality is especially important to time-sensitive information such as news, job openings, tweets etc.
</p>
<p>
<h1>
Design Goals:
</h1>
</p>
<ul>
    <li> Additions of documents must be made available to searchers immediately
    <li> Indexing must not affect search performance
    <li> Additions of documents must not fragment the index (which hurts search performance)
    <li> Deletes and/or updates of documents must not affect search performance.
    <li> ... 
</ul>
<p>
<h1>
Other features:
</h1>
</p>
<ul>
    <li> fast lucene docid to uid mapping
    <li> fast uid to lucene docid mapping (reverse id mapping)
    <li> custom MergePolicy to produce balanced index segment management for handle realtime updates
    <li> partial delete expunge for enhancing search performance without full optimize
    <li> Automatic plugin to forward-rolling index
    <li> full jmx console for indexing management/monitoring
    <li> ... 
</ul>
<h1>
Getting Started:
</h1>
Check out the following wikis:
<ul>
  <li> Code samples: <br/>
      <a href="http://snaprojects.jira.com/wiki/display/ZOIE/Code+Samples">http://linkedin.jira.com/wiki/display/ZOIE/Code+Samples</a>
  <li> Running example/demo: <br/>
      <a href="http://snaprojects.jira.com/wiki/display/ZOIE/Getting+Started+-+Zoie+Example">http://linkedin.jira.com/wiki/display/ZOIE/Getting+Started+-+Zoie+Example</a>
</ul>

<h1>
Architecture Diagram:
</h1>
<p>
<img src="images/zoie-graph.jpg"/>
</p>

<b>Disclaimer:</b>
<p>
All Zoie releases have gone through extensive functional and performance testing by LinkedIn before made public. All major versions are released after a trial period on the production environment.
</p>

<?php require "../includes/footer.php" ?>