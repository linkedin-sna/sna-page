<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>
<b>Kamikaze</b> is a utility package wrapping set implementations on sorted integer arrays. Search indexes, graph algorithms, and certain sparse matrix representations tend to make heavy use of sorted integer arrays. For example, in search engines,  an inverted index for a collection of documents is a structure that stores, for each term (word) occurring somewhere in the collection, information about the locations where it occurs. In particular, for each term t, the index contains an inverted list, which contains a sequence of sorted integer document IDs (and other information). Thus, inverted index compression techniques are concerned with compressing sequences of integers. The resulting compression ratio and decompression speed depend on the exact properties of these sequences.

<p>
Kamikaze implements <b>PForDelta</b> compression algorithm (or called P4Delta) which was recently studied and has been shown by <a href="http://www2008.org/papers/pdf/p387-zhangA.pdf">paper[1]</a> and <a href="http://www2009.org/proceedings/pdf/p401.pdf"> paper[2]</a> to be able to achieve the best trade-off of the compression ratio and decompression speed for inverted index of search engines. Many other techniques for inverted index compression have been studied in the literature; see <a href="http://books.google.com/books?id=2F74jyPl48EC&dq=managing+gigabytes&printsec=frontcover&source=bn&hl=en&ei=qMZuTKqyEIuosQOg5ZmiCw&sa=X&oi=book_result&ct=result&resnum=4&ved=0CCoQ6AEwAw#v=onepage&q&f=false">Managing Gigabytes</a> for a survey and <a href="http://www2009.org/proceedings/pdf/p401.pdf"> paper[2] </a> and <a href="http://citeseerx.ist.psu.edu/viewdoc/summary?doi=10.1.1.155.2695"> paper[3] </a> and for very recent work. Please refer to <a href="http://www2009.org/proceedings/pdf/p401.pdf"> paper[2] </a> and <a href="http://citeseerx.ist.psu.edu/viewdoc/summary?doi=10.1.1.155.2695"> paper[3] </a> for the detailed performance comparision betwen most of those techniques and <b>PForDelta</b>. 
</p>

<h2>Version 1.0.0 and Version 2.0.0</h2>
<p>
The first two versions, version 1.0.0 and version 2.0.0, provide an awesome platform to perform set operations and effcient inverted list compression/decompression. They are based on <a href="http://homepages.cwi.nl/~heman/downloads/msth
esis.pdf">the original PForDelta algorithm </a> and <a href="http://www2008.org/papers/pdf/p387-zhangA.pdf">an improved version</a> developed in <a href="http://cis.poly.edu/westlab/people.html">WestLab</a>. Currently the optimizations 
include
</p>

<ol>
   <li> Integer Array representation : Document set based on Dynamic Integer Arrays
   <li> OpenBitSet representation : Document Set based on OpenBitSet implementation from Lucene.
   <li> P4Delta representation : Document Set for sorted Integer segments compressed using a variation of the P4Delta compression algorithm.
</ol>

<h2>Version 3.0.0 </h2>

<p>
Kamikaze Version 3.0.0 (the new source codes will be released soon) inherits the architecture of the first two versions and supports the same APIs. In Version 3.0.0., the PForDelta algorithm is highly optimized such that the performance of compression/decompression and the corresponding set operations are improved significantly (Please refer to the <a href="./performance.php">Performance</a> page for the detailed experimental results). In particular, compared to the previous two versions, Version 3.0.0 can achieve
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


The Version 3.0.0 integrate the <a href="http://www2009.org/proceedings/pdf/p401.pdf"> recent implementaion </a> of PForDelta in <a href="http://cis.poly.edu/westlab/people.html">WestLab</a> with other techniques together to 
achieve the overall performance improvement.


<?php require "../includes/footer.php" ?>
