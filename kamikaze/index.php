<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>
<b>Kamikaze</b> is a utility package wrapping set implementations on document lists. It also implements PForDelta compression algorithm (called P4Delta in the earlier version 1.0.0) for sorted integer segments to enable inverted list compression for search engines like Lucene. The first version, <b>Version 1.0.0</b>, provides an awesome platform to perform set operations and effcient inverted list compression/decompression. The latest version, <b>Version 2.0.0</b> inherits the architecture of Version 1.0.0 and optimizes the PForDelta compression/decompression significantly, resulting in more efficient set operations. Both versions support the same APIs.


<h2>Version 1.0.0 </h2>

<p>
Kamikaze version 1.0.0 provides docset implementations on various underlying document id set representations for inverted lists in search engines. It is based on <a href="http://homepages.cwi.nl/~heman/downloads/msthesis.pdf">the original PForDelta algorithm </a> and <a href="http://www2008.org/papers/pdf/p387-zhangA.pdf">an improved version</a> developed in <a href="http://cis.poly.edu/westlab/people.html">WestLab</a>. Currently the optimizations include
</p>

<ol>
   <li> Integer Array representation : Document set based on Dynamic Integer Arraysi
   <li> OpenBitSet representation : Document Set based on OpenBitSet implementation from Lucene.
   <li> P4Delta representation : Document Set for sorted Integer segments compressed using a variation of the P4Delta compression algorithm.
</ol>



<h2>Version 2.0.0 </h2>

<p>
Kamikaze Version 2.0.0 (the new source codes will be released soon) inherits the architecture of Version 1.0.0. It supports the same APIs as Version 1.0.0. However, in Version 2.0.0., the PForDelta algorithm is highly optimized such that the performance of compression/decompression and the corresponding set operations are improved significantly (Please refer to the <a href="./performance.php">Performance</a> page for detailed experimental results). In particular, compared to Version 1.0.0, Version 2.0.0 can achieve
</p>

<ul>
   <li> PForDelta </li>
   <ul> 
      <li> More compact representation of document lists</li>
      <li> Faster decompression speed </li>
   </ul>
   <li> Doc Set Operations </li>
   <ul>
      <li> Faster sequential scanning (iteration) speed over the compressed lists.
      <li> Faster random lookup of docIds on the compressed lists.
      <li> Faster finding intersections of the compressed lists.
   </ul>
</ul>


The Version 2.0.0 integrate the <a href="http://www2009.org/proceedings/pdf/p401.pdf"> lately implementaion </a> of PForDelta in <a href="http://cis.poly.edu/westlab/people.html">WestLab</a> with other techniques together to 
achieve the overall performance improvement.


<?php require "../includes/footer.php" ?>
