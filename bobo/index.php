<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Bobo</h2>
<p>
Bobo is a Faceted Search implementation written purely in Java, an extension of Apache Lucene.
</p>
<p>
While Lucene is good with unstructured data, Bobo fills in the missing piece to handle semi-structured and structured data.
</p>
<p>
Bobo Browse is an information retrieval technology that provides navigational browsing into a semi-structured dataset. Beyond the result set from queries and selections, Bobo Browse also provides the facets from this point of browsing.
</p>

<h2>Logical Architecture</h2>
<img src="images/bobo-arch.png">
<h2>Features</h2>
<ul>
	<li> No need for cache warm-up for the system to perform.
    <li> multi value sort - sort documents on fields that have multiple values per doc, .e.g tokenized fields
    <li> fast field value retrieval - over 30x faster than IndexReader.document(int docid)
    <li> facet count distribution analysis
    <li> stable and small memory footprint
    <li> support for runtime faceting
    <li> result merge library for distributed facet search
</ul>
<h2>News</h2>
<p>
Release: - Bobo Browse 2.5.0 is released (<a href="http://github.com/xiaoyang/bobo/downloads">here</a>)
</p>
<p>
News: - Bobo is powering the new <a href="http://www.linkedin.com">LinkedIn Faceted People Search</a>
</p>
<p>
See <a href="http://www.techcrunch.com/2009/07/15/linkedin-drills-down-into-people-search-with-new-beta">Techcrunch article</a> covering our search improvements.

<?php require "../includes/footer.php" ?>