<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>
Quickstart for Network Typeahead Search
</h2>

<p>
The network typeahead search aims at personalizing and improving search experience by taking into consideration an individual's social network
such as friendship, followership and professional connections. Cleo makes it easy to build the backbone of
network typeahead search with a small number of lines of code and a few configuration files.
</p>

<p>
The example Java class (see <code>src/examples/java/cleo/examples/MyFriendsTypeahead.java</code>) explains
how to build typeahead search for friends. Before checking out the code, you need to understand
the configuration first.
</p>

<h3>
MyFriends Typeahead Configuration
</h3>

<p>
The <code>src/examples/resources/network-config</code> directory contains two configuration files which are <code>i001.config</code> and
<code>i002.config</code>. These two files divide a social network based on friendship into two range-based partitions with each partition containing a user-specified number of people.
For the purpose of demonstration, we set the number of people in each partition to 100,000. In the real world, you may need to use a larger number. At LinkedIn, we choose 5,000,000 for each partition and co-locate two partitions on a commodity box.
</p>

<p>
If you have ever grown your social network site to the size of LinkedIn or Facebook,
you will need a lot of partitions and boxes for horizontal scaling.
</p>

<h4>
MyFriends Typeahead Partition 1: i001.config
</h4>

<small>
<code>
<pre>
cleo.search.network.typeahead.config.name=i001
cleo.search.network.typeahead.config.partition.start=0
cleo.search.network.typeahead.config.partition.count=100000
cleo.search.network.typeahead.config.homeDir=target/network-typeahead/friends/i001

cleo.search.network.typeahead.config.elementSerializer.class=cleo.search.TypeaheadElementSerializer
cleo.search.network.typeahead.config.connectionFilter.class=cleo.search.connection.TransitivePartitionConnectionFilter

cleo.search.network.typeahead.config.elementStoreDir=${cleo.search.network.typeahead.config.homeDir}/element-store
cleo.search.network.typeahead.config.elementStoreIndexStart=${cleo.search.network.typeahead.config.partition.start}
cleo.search.network.typeahead.config.elementStoreCapacity=${cleo.search.network.typeahead.config.partition.count}
cleo.search.network.typeahead.config.elementStoreSegmentMB=32
cleo.search.network.typeahead.config.elementStoreCached=true

cleo.search.network.typeahead.config.connectionsStoreDir=${cleo.search.network.typeahead.config.homeDir}/weighted-connections-store
cleo.search.network.typeahead.config.connectionsStoreIndexStart=0
cleo.search.network.typeahead.config.connectionsStoreCapacity=1000000
cleo.search.network.typeahead.config.connectionsStoreSegmentMB=32

cleo.search.network.typeahead.config.filterPrefixLength=2
</pre>
</code>
</small>

<h4>
MyFriends Typeahead Partition 2: i002.config
</h4>

<small>
<code>
<pre>
cleo.search.network.typeahead.config.name=i002
cleo.search.network.typeahead.config.partition.start=100000
cleo.search.network.typeahead.config.partition.count=100000
cleo.search.network.typeahead.config.homeDir=target/network-typeahead/friends/i002

cleo.search.network.typeahead.config.elementSerializer.class=cleo.search.TypeaheadElementSerializer
cleo.search.network.typeahead.config.connectionFilter.class=cleo.search.connection.TransitivePartitionConnectionFilter

cleo.search.network.typeahead.config.elementStoreDir=${cleo.search.network.typeahead.config.homeDir}/element-store
cleo.search.network.typeahead.config.elementStoreIndexStart=${cleo.search.network.typeahead.config.partition.start}
cleo.search.network.typeahead.config.elementStoreCapacity=${cleo.search.network.typeahead.config.partition.count}
cleo.search.network.typeahead.config.elementStoreSegmentMB=32
cleo.search.network.typeahead.config.elementStoreCached=true

cleo.search.network.typeahead.config.connectionsStoreDir=${cleo.search.network.typeahead.config.homeDir}/weighted-connections-store
cleo.search.network.typeahead.config.connectionsStoreIndexStart=0
cleo.search.network.typeahead.config.connectionsStoreCapacity=1000000
cleo.search.network.typeahead.config.connectionsStoreSegmentMB=32

cleo.search.network.typeahead.config.filterPrefixLength=2
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
  <li><code>cleo.search.network.typeahead.config.name</code> - the name of partition or instance</li>
  <li><code>cleo.search.network.typeahead.config.homeDir</code> - the home directory of partition</li>
  <li><code>cleo.search.network.typeahead.config.partition.start</code> - the start of partition</li>
  <li><code>cleo.search.network.typeahead.config.partition.count</code> - the size of partition</li>
</ul>

<p>
The other configuration parameters can also be customized.
</p>

<ul>
  <li><code>cleo.search.network.typeahead.config.elementSerializer.class</code></li>
  <p>
  This parameter needs to be specified if one defines a new type of <code>cleo.search.Element</code> and a new serializer.
  The specified serializer is for persisting elements in the forward indexes periodically.
  </p>
  
  <li><code>cleo.search.network.typeahead.config.filterPrefixLength</code></li>
  <p>
  This parameter determines the maximum prefix length (the maximum number of characters in the prefix) acceptable to the Bloom Filter.
  The recommended value is <strong>2</strong>. The Bloom Filter calculation takes into consideration the number of characters up to the specified length. 
  </p>
  
  <li><code>cleo.search.network.typeahead.config.connectionFilter.class</code></li>
  <p>
  This parameter specifies how to filter connections (see <code>cleo.search.connection.Connection</code>) for a given partition.
  The <code>TransitivePartitionConnectionFilter</code> is a special filter designed for partitioning social network graph. 
  </p>
</ul>

<p>
 The configurations above specify that friends (elements) are stored using 32 MB files on disk and cached in memory.
The connections store (for the mapping from a person to a list of his/her friends) has an initial capacity of 1,000,000 mappings
and grows its capacity as needed. The connections store persists friendship data using 32 MB files on disk.
The <code>element-store</code> and <code>weighted-connections-store</code> manage the forward indexes and adjacency lists of friends respectively.
Over time these indexes will be compacted and merged automatically.
</p>


<h3>
MyFriends Typeahead Source Code 
</h3>

<p>
The example Java class below shows how to build typeahead search for friends from the 1st and 2nd degree of a social network. 
</p>

<small>
<code>
<pre style="background-color: #EEEEEE;">
package cleo.examples;

import java.io.File;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;

import cleo.search.Hit;
import cleo.search.Indexer;
import cleo.search.MultiIndexer;
import cleo.search.SimpleTypeaheadElement;
import cleo.search.TypeaheadElement;
import cleo.search.collector.Collector;
import cleo.search.collector.SimpleCollector;
import cleo.search.connection.ConnectionIndexer;
import cleo.search.connection.MultiConnectionIndexer;
import cleo.search.connection.SimpleConnection;
import cleo.search.selector.ScoredElementSelectorFactory;
import cleo.search.tool.WeightedNetworkTypeaheadInitializer;
import cleo.search.typeahead.MultiTypeahead;
import cleo.search.typeahead.NetworkTypeaheadConfig;
import cleo.search.typeahead.Typeahead;
import cleo.search.typeahead.TypeaheadConfigFactory;
import cleo.search.typeahead.WeightedNetworkTypeahead;

public class MyFriendsTypeahead {

  public static WeightedNetworkTypeahead<TypeaheadElement> createTypeahead(File configFile) throws Exception {
    // Create typeahead config
    NetworkTypeaheadConfig<TypeaheadElement> config =
      TypeaheadConfigFactory.createNetworkTypeaheadConfig(configFile);
    config.setSelectorFactory(new ScoredElementSelectorFactory<TypeaheadElement>());
    
    // Create typeahead initializer
    WeightedNetworkTypeaheadInitializer<TypeaheadElement> initializer = 
      new WeightedNetworkTypeaheadInitializer<TypeaheadElement>(config);
    
    return (WeightedNetworkTypeahead<TypeaheadElement>)initializer.getTypeahead();
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
   * Indexes a number of typeahead elements that represent people.
   * 
   * @param elemIndexer - the element indexer
   * @throws Exception
   */
  public static void indexElements(Indexer<TypeaheadElement> elemIndexer) throws Exception {
    Random rand = new Random();
    elemIndexer.index(createElement(5, new String[]{"jay", "kaspers"},
      "J Kaspers", "Senior Software Engineer", "/photos/00000005.png", rand.nextFloat()));
    elemIndexer.index(createElement(29, new String[]{"peter", "smith"},
      "Peter Smith", "Product Manager", "/photos/00000029.png", rand.nextFloat()));
    elemIndexer.index(createElement(167, new String[]{"steve", "jobs"},
      "Steve Jobs", "Apple CEO", "/photos/00000167.png", rand.nextFloat()));
    elemIndexer.index(createElement(1007, new String[]{"ken", "miller"},
      "Ken Miller", "Micro Blogging", "/photos/00001007.png", rand.nextFloat()));
    elemIndexer.index(createElement(2007, new String[]{"kay", "moore"},
      "Kay Moore", "", "/photos/00002007.png", rand.nextFloat()));
    elemIndexer.index(createElement(180208, new String[]{"snow", "white"},
      "Snow White", "Princess", "/photos/00180208.png", rand.nextFloat()));
    elemIndexer.index(createElement(119205, new String[]{"richard", "jackson"},
      "Richard Jackson", "Engineering Director", "/photos/00119205.png", rand.nextFloat()));
      
    elemIndexer.flush();
  }
  
  /**
   * Indexes a number of connections in the form of friendship.
   * 
   * @param connIndexer - the connection indexer
   * @throws Exception
   */
  public static void indexConnections(ConnectionIndexer connIndexer) throws Exception {
    connIndexer.index(new SimpleConnection(5, 1007, true));
    connIndexer.index(new SimpleConnection(5, 2007, true));
    connIndexer.index(new SimpleConnection(5, 780208, true));
    connIndexer.index(new SimpleConnection(167, 180208, true));
    connIndexer.index(new SimpleConnection(167, 119205, true));
    connIndexer.index(new SimpleConnection(167, 29, true));

    connIndexer.index(new SimpleConnection(1, 5, true));
    connIndexer.index(new SimpleConnection(1, 167, true));
    
    connIndexer.flush();
  }
  
  /**
   * JVM Arguments (change accordingly for different data sets):
   *   -server -Xms4g -Xmx4g
   * 
   * Program Arguments:
   *   src/examples/resources/network-config/i001.config
   *   src/examples/resources/network-config/i002.config
   */
  public static void main(String[] args) throws Exception {
    List<ConnectionIndexer> connIndexerList = new ArrayList<ConnectionIndexer>();
    List<Indexer<TypeaheadElement>> elemIndexerList = new ArrayList<Indexer<TypeaheadElement>>();
    List<Typeahead<TypeaheadElement>> searcherList = new ArrayList<Typeahead<TypeaheadElement>>();
    
    // Create indexer and searcher
    for(String filePath : args) {
      File configFile = new File(filePath);
      WeightedNetworkTypeahead<TypeaheadElement> nta = createTypeahead(configFile);
      connIndexerList.add(nta);
      elemIndexerList.add(nta);
      searcherList.add(nta);
    }
    
    ConnectionIndexer connIndexer = new MultiConnectionIndexer("Friends", connIndexerList);
    Indexer<TypeaheadElement> elemIndexer = new MultiIndexer<TypeaheadElement>("Friends", elemIndexerList);
    Typeahead<TypeaheadElement> searcher = new MultiTypeahead<TypeaheadElement>("Friends", searcherList);
    
    // Populate typeahead indexes
    indexElements(elemIndexer);
    indexConnections(connIndexer);

    // Perform typeahead searches
    Collector<TypeaheadElement> collector;
    
    System.out.println("----- id=5 query=k m");
    collector = new SimpleCollector<TypeaheadElement>();
    collector = searcher.search(5, new String[]{"k", "m"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- id=5 query=k mil");
    collector = new SimpleCollector<TypeaheadElement>();
    collector = searcher.search(5, new String[]{"k", "mil"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- id=167 query=s");
    collector = new SimpleCollector<TypeaheadElement>();
    collector = searcher.search(167, new String[]{"s"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- id=167 query=snow wh");
    collector = new SimpleCollector<TypeaheadElement>();
    collector = searcher.search(167, new String[]{"snow", "wh"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
    
    System.out.println("----- id=1 query=j");
    collector = new SimpleCollector<TypeaheadElement>();
    collector = searcher.search(1, new String[]{"j"}, collector);
    for(Hit<TypeaheadElement> h : collector.hits()) {
      System.out.println(h);
    }
  }
}
</pre>
</code>
</small>

<?php require "../includes/footer.php" ?>
