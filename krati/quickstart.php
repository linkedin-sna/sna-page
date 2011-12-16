<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Quick Start
</h1>

<p>
It is the best to go over a few lines of sample code to become familiar with Krati.
You can obtain a Krati distribution of version 0.4.2 or above.
The <code>src/examples</code> directory from the distribution contains a number of sample files such as <code>KratiDataCache.java</code> and <code>KratiDataStore.java</code>.
</p>

<p>
The <code>KratiDataStore.java</code> below shows how to create a fixed-capacity key-value store using class <code>SimpleDataStore</code>.
The store capacity is <code>1.5*keyCount</code> as defined in the <code>createDataStore</code> method. A segment factory
called <code>MemorySegmentFactory</code> is used for storing key-value pairs in main memory to provide the fastest read and write access.
You can override the <code>createSegmentFactory</code> to provide mmap-based <code>MappedSegmentFactory</code> or Java Nio channel-based
<code>ChannelSegmentFactory</code>. 
</p>

<small>
<code>
<pre>
package krati.examples;

import java.io.File;
import java.util.Random;

import krati.cds.impl.segment.SegmentFactory;
import krati.cds.impl.store.SimpleDataStore;
import krati.cds.store.DataStore;

/**
 * A fixed-capacity key-value store.
 */
public class KratiDataStore
{
    private final int _keyCount;
    private final DataStore<byte[], byte[]> _store;
    
    /**
     * Constructs KratiDataStore.
     * 
     * @param keyCount   the number of keys.
     * @param homeDir    the home directory for storing data.
     * @throws Exception if a DataStore instance can not be created.
     */
    public KratiDataStore(int keyCount, File homeDir) throws Exception
    {
        _keyCount = keyCount;
        _store = createDataStore(keyCount, homeDir);
    }
    
    /**
     * @return the underlying data store.
     */
    public final DataStore<byte[], byte[]> getDataStore()
    {
        return _store;
    }
    
    /**
     * Creates a data store instance.
     * Subclasses can override this method to provide specific DataStore implementations such as DynamicDataStore.
     */
    protected DataStore<byte[], byte[]> createDataStore(int keyCount, File storeDir) throws Exception
    {
        int capacity = (int)(keyCount * 1.5);
        return new SimpleDataStore(storeDir,
                                   capacity, /* capacity */
                                   10000,    /* update batch size */
                                   5,        /* number of update batches required to sync indexes.dat */
                                   128,      /* segment file size in MB */
                                   createSegmentFactory());
    }
    
    /**
     * Creates a segment factory.
     * Subclasses can override this method to provide a specific segment factory such as ChannelSegmentFactory and MappedSegmentFactory.
     * 
     * @return the segment factory. 
     */
    protected SegmentFactory createSegmentFactory()
    {
        return new krati.cds.impl.segment.MemorySegmentFactory();
    }
    
    /**
     * Creates data for a given key.
     * Subclasses can override this method to provide specific values for a given key.
     */
    protected byte[] createDataForKey(String key)
    {
        return ("Here is your data for " + key).getBytes();
    }
    
    /**
     * Populates the underlying data store.
     * 
     * @throws Exception
     */
    public void populate() throws Exception
    {
        for(int i = 0; i < _keyCount; i++)
        {
            String str = "key." + i;
            byte[] key = str.getBytes();
            byte[] value = createDataForKey(str);
            _store.put(key, value);
        }
        _store.sync();
    }
    
    /**
     * Perform a number of random reads from the underlying data store.
     * 
     * @param readCnt the number of reads
     */
    public void doRandomReads(int readCnt)
    {
        Random rand = new Random();
        for(int i = 0; i < readCnt; i++)
        {
            int keyId = rand.nextInt(_keyCount);
            String str = "key." + keyId;
            byte[] key = str.getBytes();
            byte[] value = _store.get(key);
            System.out.printf("Key=%s\tValue=%s%n", str, new String(value));
        }
    }
    
    /**
     * java -Xmx4G krati.examples.KratiDataStore keyCount homeDir
     * 
     * @param args
     */
    public static void main(String[] args)
    {
        try
        {
            // Parse arguments: keyCount homeDir
            int keyCount = Integer.parseInt(args[0]);
            File homeDir = new File(args[1]);
            
            // Create an instance of Krati DataStore
            File storeHomeDir = new File(homeDir, KratiDataStore.class.getSimpleName());
            KratiDataStore store = new KratiDataStore(keyCount, storeHomeDir);
            
            // Populate data store
            store.populate();
            
            // Perform some random reads from data store.
            store.doRandomReads(10);
        }
        catch(Exception e)
        {
            e.printStackTrace();
        }
    }
}
</pre>
</code>
</small>

<?php require "../includes/footer.php" ?>
