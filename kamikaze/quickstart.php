<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Quick Start
</h1>

<p>
In this page, we show the sample codes for basic set operations in Kamikaze 3.0.0. 

<h2>Mapping of classes in previous versions and Kamikaze 3.0.0</h2>
In Kamikaze 3.0.0, we provide the new implementations of PForDelta and its correpsonding set operations. We also
keep their previous implementations such that users can test the performance of both of them. Please note that the old and new implementations support the same method APIs
 except their class names. The class names of all new implmentations are prefxied with PForDetla. In particular, the following table shows the mapping of class names between 
the old and new implmentaions. 

<p align="center">
  <img src = "images/oldNewTable.png" width="600px" />
</p>

<p>
However, please note that although the class names are different, the APIs those classes suport are the same in both old and new implementations. Therefore, 
the only changes that previous users of Kamikaze need to make is to use the above table to replace the old class names with the new class names.
</p>

<p>
Kamikaze 3.0.0 also provides the unit test in PForDeltaKamikazeTest and PForDeltaPerfTest.

We then show the sample codes using Kamikaze Version 3.0.0. 
</p>

<h2>Adding docIds into an inverted list (doc set)</h2>
The first example shows how to add an list of sorted docIds into the PForDeltaDocIdSet. The docIds are compressed when they are being added into the set. 
<code>
<pre>

public DocIdSet addDocIds(ArrayList< Integer > Ids) throws Exception
{ 
  DocSet pForDeltaDocSet = PForDeltaDocSetFactory.getPForDeltaDocSetInstance(); 
  for(int Id:Ids) 
  {         
    pForDeltaDocSet.addDoc(Id);
  }
  return pForDeltaDocSet; 
} 

</pre>
</code>

<h2>Extrating (decompressing) docIds from an inverted list (doc set)</h2>
The following codes show how to iterate a compressed inverted list (doc set), that is, extrating all docIds on the list. 
<code>
<pre>
public ArrayList < Integer > getDocIds(PForDeltaDocIdSet pForDeltaDocSet)
{
  DocIdSetIterator iter = pForDeltaDocSet.iterator();
  ArrayList< Integer > Ids = new ArrayList< Integer >();
  int docId = iter.nextDoc();
   
  while(docId !=DocIdSetIterator.NO_MORE_DOCS) 
  {      
    Ids.add(docId);
    docId = iter.nextDoc();
  }
  return Ids;
} 

</pre>
</code>

<h2> Finding if a given docId exists in a compressed list (doc set)</h2>
The following codes show how to find if a given docId exists in a compressed list. 
<code>
<pre>

public boolean search(int target, PForDeltaDocIdSet pForDeltaDocSet)
{
  return pForDeltaDocSet.find(target);
} 

</pre>
</code>


<h2> Finding intersected docIds of multiple inverted lists (doc sets)</h2>
The following codes show how to find all intersected docIds of three inverted lists in a Document-at-a-time (DAAT) manner (that is, keep a pointer for each 
list and move the pointers in a synchronized way).                                                                                        
<code>
<pre>

public ArrayList< Integer >  findAndIntersections(PForDeltaDocIdSet pForDeltaDocSet1, PForDeltaDocIdSet pForDeltaDocSet2, PForDeltaDocIdSet pForDeltaDocSet3)
{
  ArrayList< DocIdSet > docs = new ArrayList< DocIdSet >();
  docs.add(pForDeltaDocSet1);
  docs.add(pForDeltaDocSet2);
  docs.add(pForDeltaDocSet3);

  ArrayList< Integer > intersectedIds = new ArrayList< Integer >();
  AndDocIdSet andSet = new AndDocIdSet(docs); 
  DocIdSetIterator iter = andSet.iterator();
  
  int docId = iter.nextDoc();
  while(docId != DocIdSetIterator.NO_MORE_DOCS)
  {
    intersectedIds.add(docId);
    docId = iter.nextDoc();
  }
   
  return intersectedIds;
} 

</pre>
</code>

<?php require "../includes/footer.php" ?>
