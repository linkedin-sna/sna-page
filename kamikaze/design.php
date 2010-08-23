<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Design
</h1>

<p>
<strong>Kamikaze</strong> is a utility package wrapping set implementations on document lists. It is designed for serving compact set representation and fast set operations. The architecture of Kamikaze (which is similar for Version 1.0.0, Verison 2.0.0 and Version 3.0.0) is shown in the following figure. 
<h2>
Architecture in Version 3.0.0:
</h2

<p align="center">
  <img src = "images/Arch.png" width="600px" />
</p>

In particular,
<ul>
 <li> On the lowest level of Kamikaze, three different classes are supported to represent the document lists: PForDeltaWithBase (which is an variant of PForDelta and can support compact data compression and fast decompression), IntArray (which represent each docId as a 32-bit integer), OpenBitSet (which represent all docIds using the OpenBitSet). One of them will be chosen to represent the data and perform the basic operations on the data, for example, representing (compressing) the docIds on the lists and retrieving (decompressing) them. PForDelta is always perferred than the other two methods except when the docIds on the lists are extremely densely distributed (that is, the list length is comparable to the value of the maximal docId) or the lists are extremly short (for example, tens of docIds).    
 </li>
 <li> On the middle level, the correponding doc sets (inverted lists) are built on top of the above three data representation classess. These doc sets support the basic doc set operations, for example, adding a document, iterating the compressed set and finding a particular docId in the compressed set, etc.
 </li>
 <li> On the top level, the basic query processing operations are supported based on the set operations in the middle level. For example, PForDeltaAndDocIdSet supports the operation of finding all intersected docIds of multiple inverted lists (doc sets), that is, the AND operation in a <a href="http://www2008.org/papers/pdf/p387-zhangA.pdf">Document-at-a-time (DAAT)</a> manner.  
 </li>
</ul>


<h2>
PForDelta in Version 3.0.0
</h2>
<h3>History</h3>
<a href="http://homepages.cwi.nl/~heman/downloads/msthesis.pdf">PForDelta</a> is a compression method that was originally proposed by S. Heman. It was not originally designed to compress inverted indexes of search engines. It was first used in 2008 by WestLab (Web Exploration and Search Technology Lab at Polytechnic Institute of New York University) to <a href="http://www2008.org/papers/pdf/p387-zhangA.pdf">compress</a> inverted indexes of search engines. Recently, WestLab proposed several other <a href="http://www2009.org/proceedings/pdf/p401.pdf"> optimizations</a>
 on PForDelta and claimed that the optimized PForDelta can achieve a better tradeoff between the compressed size and the decompression speed than other inverted index compression methods, for example, Rice coding, Variable-byte coding, Gamma coding, Interpolative coding, etc.The beauty of PForDelta is that it supports extremely fast decompression while also achieving a small compressed size. 

<h3>Basic Idea</h3>The basic idea of PForDelta is as follows: in order to compress a block of k numbers, say, 256 numbers, it first determines a value b such that most of the 256 values to be encoded (say, 90%) are less than 2^b and thus fit into a fixed bit field of b bit
s each. The remaining values, called exceptions (please note this has nothing to do with Java run time exceptions), are coded separately. The data structure of PForDelta is shown in the following figure.

<p align="center">  <img src = "images/PFD.png" width="600px" />
</p>

In the above figure, a block of 256 integers is compressed as a block of 256 b-bit slots plus some additional data appended to those slots. Most of the integers in the above block can fit into b=5 bits except two exceptions, 55 and 70. The two exceptions are coded in the same way as follows: we append their offsets within the block (expPos), that is, where the two exceptions occur, to the end of the 256 b-bit slots. We store the lower b bits of the exceptions into their corresponding slots(Lo55 and Lo70) and append their higher 32-b bits(Hi55 and Hi70) to the end of expPos, that is, expHighBits. ExpPos and expHighBits are concatenated together and compressed by another compression methods called <a href="http://www2009.org/proceedings/pdf/p401.pdf"> Simple 16</a>. 

<p>
If we apply PForDelta to blocks containing some multiple of 32 values, then decompression involves extracting 
groups of 32 b-bit values, and finally patching the result by decoding a smaller number of exceptions. This process can be implemented extremely efficiently by providing, for each value of b, an optimized method for extracting 32 b-bit values from b memory words. PForDelta can be modified and tuned in various ways by choosing different thresholds for the number of exceptions allowed, and by encoding the exceptions in different ways.
</p>

