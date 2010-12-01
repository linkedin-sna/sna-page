<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Kamikaze Uses</h2>

<p>
<b>Kamikaze</b> is a utility package for performing operations on compressed arrays of sorted integers. Search indexes, graph algorithms, and certain sparse matrix representations make heavy use of integer arrays, and special compression techniques are needed to get good compression performance on this data. 
</p>

<h3>Use in search engines</h3>
<p>
In search engines, the index is a mapping from terms to a list of documents matching that term. The documents are typically stored as a sequence of sorted integer document IDs (and other information which can also be considered as sequences of integers). Thus, inverted index compression techniques are concerned with compressing sequences of sorted integers. 
</p>

<h3>Use in sparse graph algorithms</h3>
<p>
A graph is often implemented as a sparce adjacency list of nodes, where nodes are represented by integer ids. In this case, each list can be easily organized as a sorted integer array. For example, for the social graphs in large-scale social networks like Linkedin or Facebook, each list is, for a particular member, a sequence of his or her friends user ids. The performance of many algorithms on such graphs is thus greatly affected by the efficiency of various operations on such lists. For example, in order to find all common friends of two members, we need to find all intersected member IDs of their friend lists. 
</p>

<h3>Use in sparse matrix algorithms</h3>
<p>
A matrix can be considered as an alternative implementation of a graph especially when most nodes are directly connected with each other. However, when the matrix is sparse (which is very common for the first or second degree friends in social graphs), it is more efficient to first transfer it into the adjacency lists and then do various operations on the resulting lists.
</p>

<h3>P4Delta Compression</h3>
<p>
In the above applications (large scale search engines or social networks), we often need to process a huge amount of data (arrays of integers) within milliseconds. The data often need to be compressed to be hold in main memory. Due to compression, the disk traffic and the network traffic are also greatly reduced since much less amount of data need to be communicated. We also need to be able to decompress the data very efficiently to maximize, for example, the query throughput of search engines. To achieve these goals, large search engines have been trying a lot of methods. For example, Lucene uses variable-byte coding (please refer to <a href="http://books.google.com/books?id=2F74jyPl48EC&amp;dq=managing+gigabytes&amp;printsec=frontcover&amp;source=bn&amp;hl=en&amp;ei=qMZuTKqyEIuosQOg5ZmiCw&amp;sa=X&amp;oi=book_result&amp;ct=result&amp;resnum=4&amp;ved=0CCoQ6AEwAw#v=onepage&amp;q&amp;f=false">Managing Gigabytes</a> for various inverted index compression methods) to compress indexes. Google also uses variable-byte coding to encode part of its indexes a long time ago and has switched to <a href="http://static.googleusercontent.com/external_content/untrusted_dlcp/research.google.com/en/us/people/jeff/WSDM09-keynote.pdf">other compression</a> methods lately (their new method can be seen as a variation on PForDelta, the same algorithm implemented in Kamikaze and optimized in Kamikaze version 3.0.0). Therefore, we can see that it is very important to build Kamikaze on top of a good compression method that can achieve both the small compressed size and fast decompression speed. 
</p>

<p>
Kamikaze implements PForDelta compression algorithm (or called P4Delta) which was recently studied and has been shown by <a href="http://www2008.org/papers/pdf/p387-zhangA.pdf">paper[1]</a> and <a href="http://www2009.org/proceedings/pdf/p401.pdf">paper[2]</a> to be able to achieve the best trade-off of the compression ratio and decompression speed for inverted index of search engines. Many other techniques for inverted index compression have been studied in the literature; see <a href="http://books.google.com/books?id=2F74jyPl48EC&amp;dq=managing+gigabytes&amp;printsec=frontcover&amp;source=bn&amp;hl=en&amp;ei=qMZuTKqyEIuosQOg5ZmiCw&amp;sa=X&amp;oi=book_result&amp;ct=result&amp;resnum=4&amp;ved=0CCoQ6AEwAw#v=onepage&amp;q&amp;f=false">Managing Gigabytes</a> for a survey and <a href="http://www2009.org/proceedings/pdf/p401.pdf">paper[2]</a> and <a href="http://citeseerx.ist.psu.edu/viewdoc/summary?doi=10.1.1.155.2695">paper[3]</a> and for very recent work, especially the detailed performance comparison between most of those techniques and PForDelta. Unfortunately, Lucene does not support PForDelta now although <a href="http://www2009.org/proceedings/pdf/p401.pdf">paper[2]</a> and <a href="http://citeseerx.ist.psu.edu/viewdoc/summary?doi=10.1.1.155.2695">paper[3]</a> have shown that PForDelta can achieve much better performance than variable-byte coding in terms of both compressed size and decompression speed.
</p>

<p>
Kamikaze builds an platform on top of PForDelta to perform efficient set operations and inverted list compression/decompression. Kamikaze Version 3.0.0 inherits the architecture of the first two versions and supports the same APIs. In Version 3.0.0., the PForDelta algorithm is highly optimized such that the <a href="http://sna-projects.com/kamikaze/performance.php">performance</a> of compression/decompression and the corresponding set operations are improved significantly.
</p>

<h2>Kamikaze @Linkedin</h2>
<p>
In Linkedin, Kamikaze has been used in the distributed graph team and search team. We are also looking forward to contributing to the Lucene community with the Kamikaze, especially the optimized PForDelta compression algorithm.   
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
Kamikaze Version 3.0.0 inherits the architecture of the first two versions and supports the same APIs. In Version 3.0.0., the PForDelta algorithm is highly optimized such that the performance of compression/decompression and the corresponding set operations are improved significantly (Please refer to the <a href="./performance.php">Performance</a> page for the detailed experimental results). In particular, compared to the previous two versions, Version 3.0.0 can achieve
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
