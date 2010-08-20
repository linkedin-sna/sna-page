<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Suggestion 
</h1>
As discussed in <a href="design.php">Design</a> page, on the lowest level of Kamikaze, we need to choose the best class from PForDeltaWithBase, IntArray and OpenBitSet to represent the document lists. In general, if the list is very short, for 
example, tens or hundreds of docIds on it, we choose IntArray; if the list is extremely dense (which normally means that it is extremely long), for example, the number of docIds on it is comparable to the maximal value of 
all docIds, we choose OpenBitSet; otherwise we choose PForDeltaWithBase. However, in real search engines, very commom words are usually not indexed (for example, the word "the"). 
Therefore, we are often only concerned with IntArray and PForDeltaWithBase. In this page, we give some suggestions about how to choose them through the following experiments.

<h2>Experiment 1</h2>
We build random lists with various lengths (the range of docIds is still [0,75,0000,000)) and store the resulting lists using PForDeltaDocId and IntArraySet respectively. We then compare their serialized object sizes and show the experimental results in the following figure.
   
<p align="center">
  <img src = "images/sizeComparison.png" width="600px" />
</p>
<p>
The above figure shows their serialzied object sizes for the list lengths from 100 to 100,000 and show the enlarged picture for the list lengths from 100 to 2500. From the figure, we can see that, for the randomly generated lists, 
we should choose to use IntArray if the list length is less than 1600, otherwise we should choose PForDeltaWithBase. 
</p>

<h2>Experiment 2</h2>
We then compare the processing speed between IntArraySet and the two versions of PForDelta shown in the <a href="design.php">Performance</a> page. We run similar experiments as the Experiment 5 shown in <a href="Performance.php">Performance</a> page. Besides the results we have showed in <a href="Performance.php">Performance</a> page, we also show the results of IntArraySet in the following figure: 

<p align="center">
  <img src = "images/speedComparisonWithIntArray.png" width="1000px" />
</p>
<p>
The above figure shows the processing time for the intersection operation of multiple lists. The relative performance (processing time) of IntArraySet, OLD and NEW (with regard to IntArray) is drawn in the figure. From the figure, we can see that IntArraySet can always achieve the fastest speed. However, our NEW version can also achieve comparable processing speed for various data sets. Although IntArraySet can achieve fast set operations, its size is often so huge that it cannot be completely hold in the memory. The overall processing time may be increased significantly due to transferring data from disk to memory. Therefore, search engines will turn to some kind of compression techniques to achieve the overall best performance. 
</p>

