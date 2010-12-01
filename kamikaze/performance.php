<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Performance
</h1>

<p>
The architecture of Kamikaze version 3.0.0 is based on the version 1.0.0 and version 2.0.0. The version 1.0.0 and version 2.0.0 provide an excellent platform to do set operations on document lists. Version 3.0.0 focus on optimizing PForDelta compression algorithm and the corresponding set operations. In this page, we provide the experimental results as follows:

<ul>
<li>We compare the relative performance of Kamikaze the version 2.0.0 and version 3.0.0. </li>
<li>We provide the absolute performance evaluation of Kamikaze version 3.0.0. </li>
</ul>


<h2>
Evaluation
</h2>
We evaluate Kamikaze in terms of: 
<ul>
<li> <b>The compression ratio (or the compressed size).</b> It determines the amount of main memory needed for a memory-based index, or the amount of 
the disk traffic for of a disk-based index, or the amount of network traffic for a network-based index. Please note that in Kamikaze, we store a small amount of uncompressed auxiliary data beyond the 
compressed data (achieved by PForDelta) to support fast query processing and decompression.  
 
<li> <b>The query processing speed (or the set operation speed), especially the speed to do sequential scanning and random lookup.</b> Both of them are crucial for query throughput. For example, they affect the speed of finding the intersected documents of multiple lists. The sequential scanning speed
is important for quickly iterating all doc ids in the inverted lists. The random lookup speed is crucial to find certain doc ids in the inverted list. Since the doc sets are built on top of the PForDelta-based index, the decompression performance of PForDelta plays an important role in the overall set operation speed. In contrast, compression speed is less critical since each inverted list is compressed only once during index building, and then 
decompressed many times during query processing.
</ul>

<h2>
Experimental Setup
</h2>
<ul>
  <li> Test Machine </li>
  <ul> 
    <li> MAC OS X Version 10.6.4</li>
    <li> Processor: 2 x 2.26 GHz Quad-Core Intel Xeon </li>
    <li> Memory: 24GB 1066 MHz DDR3 </li>
 </ul>  
 <li> Java </li>
 <ul>
   <li> Sun HotSpot JVM
   <li> -d64 -server -Xms2G -Xmx4G
 </ul>
</ul>

<h2>
Data Sets
</h2>
PForDelta can be used to compress/decompress any sequences of positive integers and so does Kamikaze. In the following experiments, we use inverted lists of search engines as the example of such sequences. An inverted list is essentially a sequence of N sorted positive integers (each of which is a document Id). We build an inverted list as follows: we randomly select N numbers out of 75 million positive integers within range [0,75,000,000) and sort them in the ascending order, where N is a variable. In the following experiments, we build random inverted lists with a variety of lengths and run experiments on them. From the following experimental results, we will see that version 3.0.0 can consistently achieve significant improvements over version 2.0.0 for different list lengths.     

<h2>
Others
</h2>In each of the following experiments (from Experiment 1 through Experiment 6), 
<ul>
<li>We process random inverted lists with different lengths. 
<li>For inverted lists with a certain length, we run the experiment K times (the list each time is of the same length but composed of different random data) and report the average results of the last K-1 runs (the first run is considered as the warm-up run). 
<li>We call version 3.0.0 the NEW version and version 2.0.0 the OLD version. 
<li>The compressed size is reported in Bytes/list (the size of a compressed list in bytes) or Ints/Id (how many bits are used to represent a 32-bit integer), while the processing time is reported in milliseconds(ms)/list (how many milliseconds on average are taken to process a list) or Ids/sec (how 
many Ids on average are processed within a second).  
</ul>

<h2>
Experimental Results
</h2>

<b> (1) Experiment 1 </b>
<p>
We build three inverted lists, each of which is respectively composed of 15,000, 37,500 and 75,000 numbers that are randomly selected out of 75M positive integers. We compare the performance between the OLD (version 2.0.0) version and the NEW (version 3.0.0) version. 
</p>
<p>In Figure 1.1, we compare the compressed sizes of the PForDelta-related data, where we can see that the NEW version can reduce the compressed size of the OLD version by about 50%.
</p>
<p align="center">
  <img src = "images/Exp1.png" width="800px" />
</p>


<p>In Figure 1.2, we compare the sizes of the entire serialized java objects (P4DDocIdSet for OLD and PForDeltaDocIdSet for NEW). The serialized object contains all necessary information to 
rebuild the Doc Set and decompress the PForDelta-encoded data. Therefore, it contains the PForDelta-related data shown in Figure 1.1 and some auxiliary data to speed up query processing and reconstruct the object. From Figure 1.2, we can achieve the following observations:
<ul>
<li> Compared to the results in Figure 1.1, the size of the serialized objects is slightly increased as expected.
<li> The version 3.0.0 can reduce the serialized size of version 2.0.0 by about 50%. 
</ul>
</p>
<p align="center">
  <img src = "images/Exp1-serial.png" width="800px" />
</p>

<p>In Figure 1.3, we show the compression results in the number of bits (on average) per doc id, which is equal to the serialized size divided by the list length. From Figure 1.3, we can see the following:
<ul>
<li> The relative performance of the OLD and NEW versions are the same as in Figure 1.2.
<li> Since the doc ids in the three lists are distributed very sparsely (recall the range is [0,75M)), the d-gaps(differences of consecutive doc ids) are on average very large values. As a result, we have to use more bits to represent them (about 15 bits for the NEW version and over 30 bits for the OLD version). Please note that the numbers shown in Figure 1.3 is the serialized object size per doc id instead of just the PForDelta-related Bits/Id (It contains other auxiliary data than doc ids and thus is larger than the numbers reported in other literatures).   
</ul> 
</p>
<p align="center">
  <img src = "images/Exp1-bitsPerId.png" width="800px" />
</p>

<b> (2) Experiment 2 </b>
<p>For each run, we build three new random inverted lists of longer lengths, each of which is composed of 150,000, 375,000 and 750,000 numbers (out of 75M positive integers). First, we compare the compressed sizes of the OLD and NEW versions in Figure 2.1, Figure 2.2 and Figure 2.3. We can achieve the similar observations in these figures as those in Experiment 1. We also note that compared to the results in Figure 1.3, less number of bits are needed (in Figure 2.3) to represent a doc id since the doc ids of these longer lists are more densely distributed, resulting in smaller d-gaps.

</p>
<p align="center">
  <img src = "images/Exp2.png" width="800px" />
</p>

</p>
<p align="center">
  <img src = "images/Exp2-serial.png" width="800px" />
</p>

</p>
<p align="center">
  <img src = "images/Exp2-bitsPerId.png" width="800px" />
</p>

<p>In Figure 2.4, we compare the processing time of P4DDocIdSet.nextDoc() (OLD) and PForDeltaDocIdSet.nextDoc (NEW), which indicates the time spent to iterate an entire list. We can see that the nextDoc() in the NEW version is about twice as fast as that in the OLD version for iterating doc ids of lists.
</p>

</p>
<p align="center">  <img src = "images/Exp2-time.png" width="800px" />
</p>

<p>
In Figure 2.5, we show the corresponding iteration speed in the number of processed doc ids per second for both OLD and NEW versions. We can see that the NEW version can iterate about 75-90 million doc ids per second on the compressed lists.
</p>
<p align="center">
  <img src = "images/Exp2-speed.png" width="800px" />
</p>

<b> (3) Experiment 3 </b><p>We run similar experiments on three even longer lists, each of which is composed of 1,500,000, 3,750,000 and 7,500,000 numbers out of 75M positive integers. The experimental results are shown in Figure 3.1 through Figure 3.5. From these Figures, we can see that the NEW version can achieve about 20%-30% improvement of various performances over the OLD version. The improvement percentage is slightly decreased since the doc ids on longer lists are much more densely distributed, resulting in a large amount of very small d-gaps which cannot be compressed too much anyway. 
We note that in Figure 3.3, even less number of bits (than those in Figure 2.3 and Figure 1.3) are needed to encode a doc id since the data are more densely distributed. 
 
</p> 
<p align="center"> 
  <img src = "images/Exp3.png" width="800px" />
</p>

</p>
<p align="center">  <img src = "images/Exp3-serial.png" width="800px" />
</p>

</p>
<p align="center">
  <img src = "images/Exp3-bitsPerId.png" width="800px" />
</p>
</p>
<p align="center">  <img src = "images/Exp3-time.png" width="800px" /></p>

</p>
<p align="center">
  <img src = "images/Exp3-speed.png" width="800px" />
</p>

<b> (4) Experiment 4 </b><p>Finally, we run experiments on very short lists, each of which is composed of 1,500, 3,750 and 7,500 numbers out of 75M positive integers. We only show the compressed size since the lists are so short that it is hard to measure the processing speed precisely. From Figure 4.1 and Figure 4.2, we can see that the NEW version can still reduce the compression size of the OLD version by about 20%-40%.  
 
</p>
<p align="center">
  <img src = "images/Exp4.png" width="800px" />
</p>

</p>
<p align="center">  <img src = "images/Exp4-serial.png" width="800px" />
</p>

</p>
<p align="center">
  <img src = "images/Exp4-bitsPerId.png" width="800px" />
</p>

<b> (5) Experiment 5 </b><p>In Figure 5.1, we compare the processing speed of P4DAndDocIdSet.nextDoc() and PForDeltaAndDocIdSet.nextDoc() (please note that they are different from the P4DDocIdSet.nextDoc() and PForDeltaDocIdSet.nextDoc() operations shown in the above other Experiments), either of which indicates the speed of finding the intersected doc ids of multiple (for example, three) random lists with various list lengths. We show in Figure 5.1 the percentage of the time reduction of the NEW version over the OLD version. From the figure, we can see that this operation of the NEW version is about twice as fast as that of the OLD version.  
</p>
<p align="center">
  <img src = "images/Exp5.png" width="1000px" />
</p>

<b> (6) Experiment 6 </b><p>In Figure 6.1, we compare the processing speed of the P4DDocIdSet.find() and PForDeltaDocIdSet.find(), either of which detects if a given docId exists in the compressed list. In particular, for each doc id in the first list, we search in the other two lists to see if it exists in them. We show in Figure 6.1 the percentage of the time reduction of the NEW version over the OLD version. From Figure 6.1, we can see that this operation in the NEW version is about twice as fast as that in the OLD version.                                                                                                                                                                                       
</p>
<p align="center">
  <img src = "images/Exp6.png" width="1000px" />
</p>

<h2>
Conclusions
</h2>
<p> In summary, from the above experiments (Experiment 1 through Experiment 6), we can see that for random lists with various lengths, the Kamikaze version 3.0.0 can achieve significant improvements consistently over the version 2.0.0, in terms of both compressed data sizes and the processing speed. 
</p>

<p>
For any questions, please contact hyan2008@gmail.com
</p>
<?php require "../includes/footer.php" ?>

