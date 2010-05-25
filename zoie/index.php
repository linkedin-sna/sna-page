<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<p>
Zoie is a real-time search and indexing system built on Apache Lucene.
</p>
<p>
News: Zoie 2.0.0-rc2 is released (12/14/2009) - Compatible with Lucene 2.9.x.
</p>
<p>
Originally developed at LinkedIn.com.
</p>
<p>
Donated by LinkedIn.com on July 19, 2008.
</p>
<p>
Zoie is a mature open source project and has been deployed in a real-time large-scale consumer website: LinkedIn.com handling millions of searches as well as hundreds of thousands of updates daily.
</p>
<p>
All Zoie releases have gone through extensive functional and performance testing by LinkedIn before made public. All major versions are released after a trial period on the production environment.
</p>
<p>
In a real-time search/indexing system, a document is made available as soon as it is added to the index. This functionality is especially important to time-sensitive information such as news, job openings, tweets etc.
</p>
<p>
This poses the following challenges which Zoie addresses:
</p>
<ul>
    <li> Additions of documents must be made available to searchers immediately
    <li> Indexing must not affect search performance
    <li> Additions of documents must not fragment the index (which hurts search performance)
    <li> Deletes and/or updates of documents must not affect search performance.
    <li> ... 
</ul>
<p>
Additional Zoie features:
</p>
<ul>
    <li> fast lucene docid to uid mapping
    <li> fast uid to lucene docid mapping (reverse id mapping)
    <li> custom MergePolicy to handle realtime updates
    <li> partial delete expunge for enhancing search performance without full optimize
    <li> balanced index segment management
    <li> full jmx console for indexing management/monitoring
    <li> ... 
</ul>
<p>
Architecture Diagram:
<img src="images/zoie-graph.jpg"/>
</p>
<?php require "../includes/footer.php" ?>