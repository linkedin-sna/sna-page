<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>What is Kamikaze ?</h2>

<p>
<b>Kamikaze</b> is a utility package for effectively compressing sorted integer arrays, which are represented as docIdSets, and performing highly efficient operations on the compressed arrays or docIdSets. Kamikaze represents the compressed integer arrays as integer sets and calls them docIdSets (the docIdSet concept is similar to that used in <a href="http://lucene.apache.org/">Lucene</a>). Kamikaze can achieve an extremely fast decompression speed with a decent compression ratio on sorted arrays (or docIdSets). It can efficiently find the intersection or the union of N compressed arrays (or docIdSets), quickly detect the existence of an given integer in the compressed arrays (or docIdSets), etc. 
</p>

<h2>Why is Kamikaze useful ?</h2>
<p>
Traditionally, the compression techniques are used to save storage space on disks. More interestingly, in large-scale distributed system, they can be used to reduce the expensive costs of I/O traffic and network traffic. Various compression techniques on sorted integer arrays have been widely used in commercial search engines, for example, Google and Yahoo!, and in open-source search engine - Lucene. Such large-scale systems have shown that compression techniques can significantly improve the overall system performance, although they introduces an additional CPU cost of decompressing the compressed data. 
</p>

<h2>Where can Kamikaze be used ?</h2>
Search indexes, graph algorithms, and certain sparse matrix representations make heavy use of compressed integer arrays. 

<p>
<b>Use in search engines</b>: The inverted index is used in search engines for efficient query processing. The index is a mapping from terms to lists of documents matching those terms. 
</p>

<p align="center">
  <img src = "images/search.png" width="600px" name="Basic Search Architecture"/> 
</p>

<p>
The basic steps of both indexing and query processing are as follows (shown in the above figure):
</p>

<p>
During the indexing process, search engines convert the documents into inverted lists. An inverted list is for a particular term a sequence of document IDs (and other information which can also be considered as sequences of integers). Search engines often compress the inverted lists (at step2 in the above figure) before they write them to the persistent storage - disks at a cluster of machines. 
</p>

<p>
During query processing, given a query of K terms, the search engine often needs to do at least the following things: First, the engine loads inverted lists (related to those terms) from disks to memory. In a distributed environment, it might also involve a large amount of data transmission over network. Kamikaze can reduce the data size and thus the cost of disk and network traffic significantly.  Second, the engine finds all documents on the compressed lists that contain most of the terms (at step6 in the above figure). This process often requires extremely fast decompression and look-up operations on compressed data, which can be done by Kamikaze in a very efficient way. Finally, the engine calculates the rankings for the matched documents and returns the documents with the highest rankings. 
</p>

<p>
<b>Use in sparse graph algorithms</b> : A graph is often implemented as a sparce adjacency list of nodes, where nodes are represented by integer ids. In this case, each list can be easily organized as a sorted integer array. For example, for the social graphs in large-scale social networks like Linkedin or Facebook, each list is, for a particular member, a sequence of his or her friends user ids. The performance of many algorithms on such graphs is thus greatly affected by the efficiency of various operations on such lists. For example, in order to find all common friends of two members, we need to find all intersected member IDs of their friend lists. 
</p>

<p>
<b>Use in sparse matrix algorithms</b> : A matrix can be considered as an alternative implementation of a graph especially when most nodes are directly connected with each other. However, when the matrix is sparse (which is very common for the first or second degree friends in social graphs), it is more efficient to first transfer it into the adjacency lists and then do various operations on the resulting lists.
</p>

<h3>The Magic of Kamikaze: P4Delta Compression</h3>
<p>
In the above applications (large scale search engines or social networks), we often need to process a huge amount of data (arrays of integers) within milliseconds. The data often need to be compressed to be hold in main memory. Due to compression, the disk traffic and the network traffic are also greatly reduced since much less amount of data needs to be communicated. We also need to be able to decompress the data very efficiently to maximize, for example, the query throughput of search engines.
</p>

<p>
To achieve these goals, large search engines have been trying a lot of methods. For example, Lucene uses variable-byte coding (please refer to <a href="http://books.google.com/books?id=2F74jyPl48EC&amp;dq=managing+gigabytes&amp;printsec=frontcover&amp;source=bn&amp;hlndexing process, search engines convert the documents into inverted lists. An inverted list is for a particular term a sequence of document IDs (and other information which can also be considered as sequences of integers). Search engines often compress the inverted lists before they write them to the persistent storage - disks at a cluster of machines.
</p>

<p>
During query processing, given a query of K terms, the search engine often needs to do at least the following things: First, the engine loads inverted lists (related to those terms) from disks to memory. In a distributed environment, it might also involve a large amount of data transmission over network. Kamikaze can reduce the data size and thus the cost of disk and network traffic significantly.  Second, the engine finds all documents on the compressed lists that contain most of the terms. This process often requires extremely fast decompression and look-up operations on compressed data, which can be done by Kamikaze in a very efficient way. Finally, the engine calculates the rankings for the matched documents and returns the documents with the highest rankings. Kamikaze has nothing to do with this last step.
</p>

=en&amp;ei=qMZuTKqyEIuosQOg5ZmiCw&amp;sa=X&amp;oi=book_result&amp;ct=result&amp;resnum=4&amp;ved=0CCoQ6AEwAw#v=onepage&amp;q&amp;f=false">Managing Gigabytes</a> for various inverted index compression methods) to compress indexes. Google also uses variable-byte coding to encode part of its indexes a long time ago and has switched to <a href="http://static.googleusercontent.com/external_content/untrusted_dlcp/research.google.com/en/us/people/jeff/WSDM09-keynote.pdf">other compression</a> methods lately (their new method can be seen as a variation on PForDelta, the same algorithm implemented in Kamikaze and optimized in Kamikaze version 3.0.0). Therefore, we can see that it is very important to build Kamikaze on top of a good compression method that can achieve both the small compressed size and fast decompression speed. 
</p>

<p>
Kamikaze implements PForDelta compression algorithm (or called P4Delta) which was recently studied and has been shown by <a href="http://www2008.org/papers/pdf/p387-zhangA.pdf">paper[1]</a> and <a href="http://www2009.org/proceedings/pdf/p401.pdf">paper[2]</a> to be able to achieve the best trade-off of the compression ratio and decompression speed for inverted index of search engines. Many other techniques for inverted index compression have been studied in the literature; see <a href="http://books.google.com/books?id=2F74jyPl48EC&amp;dq=managing+gigabytes&amp;printsec=frontcover&amp;source=bn&amp;hl=en&amp;ei=qMZuTKqyEIuosQOg5ZmiCw&amp;sa=X&amp;oi=book_result&amp;ct=result&amp;resnum=4&amp;ved=0CCoQ6AEwAw#v=onepage&amp;q&amp;f=false">Managing Gigabytes</a> for a survey and <a href="http://www2009.org/proceedings/pdf/p401.pdf">paper[2]</a> and <a href="http://citeseerx.ist.psu.edu/viewdoc/summary?doi=10.1.1.155.2695">paper[3]</a> and for very recent work, especially the detailed performance comparison between most of those techniques and PForDelta. Unfortunately, Lucene does not support PForDelta now although <a href="http://www2009.org/proceedings/pdf/p401.pdf">paper[2]</a> and <a href="http://citeseerx.ist.psu.edu/viewdoc/summary?doi=10.1.1.155.2695">paper[3]</a> have shown that PForDelta can achieve much better performance than variable-byte coding in terms of both compressed size and decompression speed.
</p>

<p>
Kamikaze builds an platform on top of PForDelta to perform efficient set operations and inverted list compression/decompression. Kamikaze Version 3.0.0 inherits the architecture of the first two versions and supports the same APIs. In Version 3.0.0., the PForDelta algorithm is highly optimized such that the <a href="http://sna-projects.com/kamikaze/performance.php">performance</a> of compression/decompression and the corresponding set operations are improved significantly.
</p>

<p>
The PForDetla algorithm is implemented in Kamikaze as independent utility classes, supporting simple compression and decompression APIs.  
</p>

<h2>Kamikaze @Linkedin</h2>
<p>
In Linkedin, Kamikaze has been used in the distributed graph team and search team, for representing over 100 million members.
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

<h2>Version 3.0.5 </h2>
<p>
Version 3.0.5 has been released recently (Nov.1st, 2011). In Version 3.0.3, Kamikaze fixed a bug that the older versions can only compress and decompress integers less than 2 to 28 (instead of the normal 32-bit integer range). All versions since Version 3.0.3 are able to support normal 32-bit integers.
</p>

<h2>Version 3.0.6 </h2>
<p>
Version 3.0.6 has been released on Jan.4th, 2012. In Version 3.0.6, Kamikaze resolved the docIdSet serialization backward compatibility issue. It replaced Java serialization with its own serialziation for PForDeltaDocIdSet, which significantly improves the serialization and deserialization speed. In addition, it resolved the null pointer exception encoutered when DocIdSet returns null iterators. This version upgraded Lucene to Lucene-3.5.0.
</p>
<?php require "../includes/footer.php" ?>
