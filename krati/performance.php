<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Performance
</h1>

<p>
The Krati distribution has included a number of tests for collecting performance statistics.
If you have a Krati distribution with versions 0.3.4 and above, you can simply run the command
below to collect read/write throughput and latency numbers on your own computer.
<br/>
<code>ant test.loggc -Dtests.to.run=TestDataCache</code>
</p>

<p>
If you want to evaluate DataCache with MappedSegment and ChannelSegment, you can run the following commands respectively.
<br/>
<code>ant test.loggc -Dtests.to.run=TestDataCacheMapped</code>
<br/>
<code>ant test.loggc -Dtests.to.run=TestDataCacheChannel</code>
</p>

<p>
The Krati distribution also includes other peroformance tests on data store classes.
You might want to play with these tests to get a general idea of Krati performance.
</p>

<h2>
Test Configuration
</h2>

<p>
This page provides a quick glance of Krati performance. The performance figures were collected using the setup below: 
</p>

<ul>
<li>Krati Configuration</li>
  <ul>
    <li>1 Writer </li>
    <li>4 Readers </li>
    <li>Data Size: 0.5~2 KB, Avg. 1 KB </li>
    <li>Batch Size: 10,000 </li>
    <li>Segment Size: 256 MB </li>
    <li>Member Count: 5,000,000 (typical partition size) </li>
  </ul>
<li>Test Machine</li>
  <ul>
    <li>Mac OS X Version: 10.5.8 </li>
    <li>Processor: 2 x 2.26 GHz Quad-Core Intel Xeon </li>
    <li>Memory: 24 GB 1066 MHz DDR3 </li>
    <li>Startup Disk: Macintosh HD </li>
  </ul>
<li>Java 6</li>
  <ul>
    <li>Sun Hotspot JVM </li>
    <li>-server -Xmx16G </li>
  </ul>
</ul>

<h2>
Read/Write Throughput
</h2>

<p>
The write throughput is approximately 20~30 writes per millisecond for MemorySegment and roughly 10~20 writes per millisecond for MappedSegment and ChannelSegment.
The persistency and recovery achieved via disk files and redo logs have an impact on write throughput.
</p>

<p align="center">
  <img src = "images/krati_data_cache_write_throughput.jpg" width="500px" />
</p>

<p>
The read throughput is approximately 1000~1200 writes per reader thread per millisecond for MemorySegment. It is an order of magnitude faster than throughput obtained using MappedSegment or ChannelSegment.
</p>

<p align="center">
  <img src = "images/krati_data_cache_read_throughput.jpg" width="500px" />
</p>

<h2>
Read/Write Latency
</h2>

<p>
The ChannelSegment has the highest write latency. As shown in the figure below, approximately 80% of writes have a latency between 10 and 50 microseconds.
The write latencies for MemorySegment and MappedSegment are approximately on the same level with the majority of writes finished between 1 and 10 microseconds.
</p>

<p align="center">
  <img src = "images/krati_data_cache_write_latency.jpg" width="500px" />
</p>

<p>
The read latency for MemorySegment is under 1 microsecond. In contrast, over 95% of reads from ChannelSegment range between 10 and 50 microseconds.
MappedSegment is in the middle.
</p>

<p align="center">
  <img src = "images/krati_data_cache_read_latency.jpg" width="500px" />
</p>

<p>
For an in-depth comparison of Krati and BDB JE, please refer to <a href="slides/krati_vs_bdb.pdf"><b>A Thorough Look at Krati vs. BDB JE - A Comparison of Throughput, Latency and GC</b></a>.
</p>

<?php require "../includes/footer.php" ?>
