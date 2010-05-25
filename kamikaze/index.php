<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

Kamikaze is a utility package wrapping set implementations on document lists. It also implements P4Delta compression algorithm for sorted integer segments to enable Inverted List compression for search engines like Lucene.

<h2>Details</h2>

<p>
Kamikaze version 1.0.0 provides docset implementations on various underlying document id set representations for inverted lists in search engines. Currently the supported implementations include
</p>

<ol>
   <li> Integer Array representation : Document set based on Dynamic Integer Arrays
   <li> OpenBitSet representation : Document Set based on OpenBitSet implementation from Lucene.
   <li> P4Delta representation : Document Set for sorted Integer segments compressed using a variation of the P4Delta compression algorithm. 
</ol>

<h2>References</h2>

<ul>
<li> <a href="http://homepages.cwi.nl/~heman/downloads/msthesis.pdf">http://homepages.cwi.nl/~heman/downloads/msthesis.pdf</a>
<li> <a href="http://www2008.org/papers/pdf/p387-zhangA.pdf">http://www2008.org/papers/pdf/p387-zhangA.pdf</a>
</ul>

<p>
The library also provides elementary set (AND|OR|NOT) operations on DocSets without materializing the final document set, this is extremely useful for large sorted integer segments.
</p>

<?php require "../includes/footer.php" ?>