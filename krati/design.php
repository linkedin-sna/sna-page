<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Design
</h1>

<p>
<strong>Krati</strong> is mainly designed for serving simple reads and writes (e.g., key-value access) from memory and providing persistency in the meanwhile.
It is intended for read-write-intensive applications with relaxed transactional semantics. 
</p>

<h2>
Design Considerations:
</h2>

<ul>
    <li> Simple Data Model:
      <ul>
        <li> Varying-length data array</li>
        <li> Key-value data store</li>
      </ul>
    </li>
    <li> Multi-Reader and Single Writer 
       <ul>
         <li> Append-only writes</li>
         <li> Concurrent reads and writes</li>
     	</ul>
    </li>
    <li> Persistency:
       <ul>
          <li> Write-ahead redo log</li>
          <li> Periodic checkpointing</li>
          <li> Writes persisted to disk in batch</li>
       </ul>
    </li>
    <li> Performance:
      <ul>
         <li> Low read/write latency</li>
         <li> High read/write throughput</li>
      </ul>
    </li>
    <li> Hash-based indexing</li>
      <ul>
         <li> Fast equality search</li>
         <li> Random reads and writes</li>
      </ul>
    <li> Automatic data compaction</li>
    <li> Java-based:
      <ul>
         <li> Java Nio-enabled</li>
         <li> Java GC friendly</li>
      </ul>
    </li>
</ul>

<h2>
Architectural Overview:
</h2>
<p>
The conceptual architecture of Krati is composed of three layers.
The top layer is the content data store service API, which includes array-like set/get methods and standard key-value store get/put/delete methods.
The bottom layer provides Java NIO-based persistency to back up data segments, indexes, and meta data via disk files.
</p>

<p>
The layer in the middle manages data segments, data indexes, and write-ahead redo logic.
Segment Manager is responsible for segment creation, recycle and compaction.
Index Manager uses hash functions to map keys to memory-resident array indexes.
It does automatic batch-based flushing to sync data, indexes, and meta data to disk files.
Data Handler allows customization of data to put into segments.
</p>

<p>
Krati segments can be thought of pure data blocks backed by files on disk. Every segment contains a number of data elements.
The index manager provides logic for retrieving indexes to data elements in a segment.
Krati always keeps indexes in memory for better performance.
</p.

<p>
Krati supports three types of segments:
 <ul>
   <li><b>MemorySegement</b>:
   Memory resident and designed for extremely fast reads and writes.
   It works for small data sets that fit into memory.
   </li>
   <li><b>MappedSegement</b>:
   I/O page cache resident via Java/NIO mmap and designed for relatively fast reads and writes.
   It works for relatively large data sets that do not fit into memory.
   </li>
   <li><b>ChannelSegement</b>:
   I/O page cache resident and designed for relatively slow reads and writes.
   It works for very large data sets that cannot fit into memory.
   </li>
 </ul>
</p>

<p align="center">
  <img src="images/krati_architecture.jpg" />
</p>

<p>
The following diagram shows the internal implementation of the Krati main class SimpleDataArray.
Multiple readers can issue concurrent reads via DataArray get methods.
There is one and only one writer, which does append-only writes to data segments via DataArray set methods.
The writer periodically starts a compactor to perform segment compaction and reclaim wasted data space. 
</p>

<p align="center">
  <img src="images/krati_internal_architecture.jpg" />
</p>

<?php require "../includes/footer.php" ?>
