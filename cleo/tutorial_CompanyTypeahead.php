<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>
Quickstart for Generic Typeahead Search
</h2>

<p>
To build generic typeahead search for a specific data domain (e.g., companies), you first produce a required configuration
or multiple configurations for horizontal scaling if the data set is large. Based on the specified configuration(s), you can then 
create generic typeahead search instances. Taking a look at the company typeahead example,
you will find it pleasantly simple to build generic typeahead search using Cleo.
</p>

<h3>
Company Typeahead Configuration
</h3>

<p>
The <code>src/examples/resources/company-config</code> directory contains two configuration files which are <code>i001.config</code> and
<code>i002.config</code>. These two files divide companies into two range-based partitions with each partition containing one million companies.
If there are more companies, you can increase the partition size (<code>cleo.search.generic.typeahead.config.partition.count</code>) or the number of partitions. 
</p>

<h4>
Company Typeahead Partition 1: i001.config
</h4>

<small>
<code>
<pre>
cleo.search.generic.typeahead.config.name=i001
cleo.search.generic.typeahead.config.partition.start=0
cleo.search.generic.typeahead.config.partition.count=1000000
cleo.search.generic.typeahead.config.homeDir=target/generic-typeahead/company/i001

cleo.search.generic.typeahead.config.elementSerializer.class=cleo.search.TypeaheadElementSerializer

cleo.search.generic.typeahead.config.elementStoreDir=${cleo.search.generic.typeahead.config.homeDir}/element-store
cleo.search.generic.typeahead.config.elementStoreIndexStart=${cleo.search.generic.typeahead.config.partition.start}
cleo.search.generic.typeahead.config.elementStoreCapacity=${cleo.search.generic.typeahead.config.partition.count}
cleo.search.generic.typeahead.config.elementStoreSegmentMB=32
cleo.search.generic.typeahead.config.elementStoreCached=true

cleo.search.generic.typeahead.config.connectionsStoreDir=${cleo.search.generic.typeahead.config.homeDir}/connections-store
cleo.search.generic.typeahead.config.connectionsStoreCapacity=1000000
cleo.search.generic.typeahead.config.connectionsStoreSegmentMB=32
cleo.search.generic.typeahead.config.connectionsStoreIndexSegmentMB=8

cleo.search.generic.typeahead.config.filterPrefixLength=2
cleo.search.generic.typeahead.config.maxKeyLength=5
</pre>
</code>
</small>

<h4>
Company Typeahead Partition 2: i002.config
</h4>

<small>
<code>
<pre>
cleo.search.generic.typeahead.config.name=i002
cleo.search.generic.typeahead.config.partition.start=1000000
cleo.search.generic.typeahead.config.partition.count=1000000
cleo.search.generic.typeahead.config.homeDir=target/generic-typeahead/company/i002

cleo.search.generic.typeahead.config.elementSerializer.class=cleo.search.TypeaheadElementSerializer

cleo.search.generic.typeahead.config.elementStoreDir=${cleo.search.generic.typeahead.config.homeDir}/element-store
cleo.search.generic.typeahead.config.elementStoreIndexStart=${cleo.search.generic.typeahead.config.partition.start}
cleo.search.generic.typeahead.config.elementStoreCapacity=${cleo.search.generic.typeahead.config.partition.count}
cleo.search.generic.typeahead.config.elementStoreSegmentMB=32
cleo.search.generic.typeahead.config.elementStoreCached=true

cleo.search.generic.typeahead.config.connectionsStoreDir=${cleo.search.generic.typeahead.config.homeDir}/connections-store
cleo.search.generic.typeahead.config.connectionsStoreCapacity=1000000
cleo.search.generic.typeahead.config.connectionsStoreSegmentMB=32
cleo.search.generic.typeahead.config.connectionsStoreIndexSegmentMB=8

cleo.search.generic.typeahead.config.filterPrefixLength=2
cleo.search.generic.typeahead.config.maxKeyLength=5
</pre>
</code>
</small>

<h4>
The Meanings of Configuration Params
</h4>

<p>
In general, you only need to determine the values of the four configuration parameters below for each typeahead partition and keep the rest not changed.
Every partition need to have a unique name and a unique home directory.
However, it is not required that all the partitions have the same size.
</p>

<ul>
  <li><code>cleo.search.generic.typeahead.config.name</code> - the name of partition or instance</li>
  <li><code>cleo.search.generic.typeahead.config.homeDir</code> - the home directory of partition</li>
  <li><code>cleo.search.generic.typeahead.config.partition.start</code> - the start of partition</li>
  <li><code>cleo.search.generic.typeahead.config.partition.count</code> - the size of partition</li>
</ul>

<p>
The other configuration parameters can also be customized.
</p>

<ul>
  <li><code>cleo.search.generic.typeahead.config.elementSerializer.class</code></li>
  <p>
  This parameter needs to be specified if one defines a new type of <code>cleo.search.Element</code> and a new serializer.
  The specified serializer is for persisting elements in the forward indexes periodically.
  </p>
  
  <li><code>cleo.search.generic.typeahead.config.filterPrefixLength</code></li>
  <p>
  This parameter determines the maximum prefix length (the maximum number of characters in the prefix) acceptable to the Bloom Filter.
  The recommended value is <strong>2</strong>. The Bloom Filter calculation takes into consideration the number of characters up to the specified length. 
  </p>
  
  <li><code>cleo.search.generic.typeahead.config.maxKeyLength</code></li>
  <p>
  This parameter determines the maximum length of terms in the inverted indexes. The recommended value is <strong>5</strong>.
  The prefixes of a word with the length no greater than the specified value are treated as indexable terms
  in the inverted list. 
  </p>
</ul>

<p>
The configurations above specify that companies (elements) are stored using 32 MB files on disk and cached in memory.
The connections store (for the mapping from prefixes to lists of company IDs) has an initial capacity of 1000000. It stores data using 32 MB files on disk and looks up the list of company IDs by a prefix using 8 MB index segments in memory.
The <code>element-store</code> and <code>connections-store</code> manage the forward indexes and inverted indexes respectively.
Over time these indexes will be compacted and merged automatically.
</p>

<h3>
Company Typeahead Source Code
</h3>

<p>
With the configurations shown above, it is straightforward to initialize each typeahead partition
and create the indexer and the searcher respectively. Then client applications can
perform indexing and searching in parallel.
</p>

<small>
<code>
<pre style="background-color: #EEEEEE;">
package cleo.examples;

import java.io.File;
import java.util.ArrayList;
import java.util.List;

import cleo.search.Hit;
import cleo.search.Indexer;
import cleo.search.MultiIndexer;
import cleo.search.SimpleTypeaheadElement;
import cleo.search.TypeaheadElement;
import cleo.search.collector.Collector;
import cleo.search.collector.SortedCollector;
import cleo.search.selector.ScoredElementSelectorFactory;
import cleo.search.tool.GenericTypeaheadInitializer;
import cleo.search.typeahead.GenericTypeahead;
import cleo.search.typeahead.GenericTypeaheadConfig;
import cleo.search.typeahead.MultiTypeahead;
import cleo.search.typeahead.Typeahead;
import cleo.search.typeahead.TypeaheadConfigFactory;

public class CompanyTypeahead {
  
  /**
   * Creates a new GenericTypeahead based on its configuration file.
   * 
   * @param configFile
   * @throws Exception
   */
  public static GenericTypeahead<TypeaheadElement> createTypeahead(File configFile) throws Exception {
    // Create typeahead config
    GenericTypeaheadConfig<TypeaheadElement> config =
      TypeaheadConfigFactory.createGenericTypeaheadConfig(configFile);
    config.setSelectorFactory(new ScoredElementSelectorFactory<TypeaheadElement>());
    
    // Create typeahead initializer
    GenericTypeaheadInitializer<TypeaheadElement> initializer =
      new GenericTypeaheadInitializer<TypeaheadElement>(config);
    
    return (GenericTypeahead<TypeaheadElement>)initializer.getTypeahead();
  }
  
  /**
   * Creates a new TypeaheadElement.
   * 
   * @param elementId - the element Id
   * @param terms     - the index terms
   * @param line1     - the display line1 (e.g. title)
   * @param line2     - the display line2 (e.g. description)
   * @param media     - the media URL
   * @param score     - the ranking score
   * @return a new TypeaheadElement
   */
  public static TypeaheadElement createElement(
    int elementId, String[] terms, String line1, String line2, String media, float score) {
    TypeaheadElement elem = new SimpleTypeaheadElement(elementId);
    elem.setTerms(terms);
    elem.setLine1(line1);
    elem.setLine2(line2);
    elem.setMedia(media);
    elem.setScore(score);
    elem.setTimestamp(System.currentTimeMillis());
    return elem;
  }
  
  /**
   * Index a number of elements that represent companies.
   * 
   * @param indexer - the element indexer
   * @throws Exception
   */
  public static void indexElements(Indexer<TypeaheadElement> indexer) throws Exception {
    // Add elements to the index 
    indexer.index(createElement(
        1307,
        new String[] {"fidelity", "investments"},
        "Fidelity Investments", "Financial Services", "/media/a101af", 0.27f));
    indexer.index(createElement(
        1337,
        new String[] {"linkedin", "lnkd"},
        "LinkedIn", "Professional Social Network", "/media/a105cd", 0.62f));
    indexer.index(createElement(
        12653,
        new String[] {"linden", "lab"},
        "Linden Lab", "Computer Software", "/media/fbb123", 0.12f));
    indexer.index(createElement(
        10667,
        new String[] {"facebook"},
        "Facebook", "The Largest Social Network", "/media/0235de", 0.71f));
    indexer.index(createElement(
        108137,
        new String[] {"lab126", "126"},
        "Lab126", null, "/media/0de5d1", 0.021f));
    indexer.index(createElement(
        1432416,
        new String[] {"funny"},
        "Funny", null, "/media/0235de", 0.001f));
    
    indexer.flush();
  }
  
  /**
   * JVM Arguments (change accordingly for different data sets):
   *   -server -Xms4g -Xmx4g
   *   
   * Program Arguments:
   *   src/examples/resources/company-config/i001.config
   *   src/examples/resources/company-config/i002.config
   */
  public static void main(String[] args) throws Exception {
    List<Indexer<TypeaheadElement>> indexerList = new ArrayList<Indexer<TypeaheadElement>>();
    List<Typeahead<TypeaheadElement>> searcherList = new ArrayList<Typeahead<TypeaheadElement>>();
    
    // Create indexer and searcher
    for(String filePath : args) {
      File configFile = new File(filePath);
      GenericTypeahead<TypeaheadElement> gta = createTypeahead(configFile);
      indexerList.add(gta);
      searcherList.add(gta);
    }
    
    Indexer<TypeaheadElement> indexer = new MultiIndexer<TypeaheadElement>("Company", indexerList);
    Typeahead<TypeaheadElement> searcher = new MultiTypeahead<TypeaheadElement>("Company", searcherList);
    
    // Populate typeahead indexes
    indexElements(indexer);
    
    // Perform typeahead searches
    Collector<TypeaheadElement> collector;
    
    System.out.println("----- query=l");
    collector = new SortedCollector<TypeaheadElement>(10, 100);
    collector = searcher.search(0, new String[] {"l"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- query=lin");
    collector = new SortedCollector<TypeaheadElement>(10, 100);
    collector = searcher.search(0, new String[] {"lin"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- query=link");
    collector = new SortedCollector<TypeaheadElement>(10, 100);
    collector = searcher.search(0, new String[] {"link"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- query=f");
    collector = new SortedCollector<TypeaheadElement>(10, 100);
    collector = searcher.search(0, new String[] {"f"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- query=fu");
    collector = new SortedCollector<TypeaheadElement>(10, 100);
    collector = searcher.search(0, new String[] {"fu"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- query=lab");
    collector = new SortedCollector<TypeaheadElement>(10, 100);
    collector = searcher.search(0, new String[] {"lab"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- query=lab1");
    collector = new SortedCollector<TypeaheadElement>(10, 100);
    collector = searcher.search(0, new String[] {"lab1"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- query=investment fi");
    collector = new SortedCollector<TypeaheadElement>(10, 100);
    collector = searcher.search(0, new String[] {"investment", "fi"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
  }
}
</pre>
</code>
</small>

<?php require "../includes/footer.php" ?>
