<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<p>
Matrix algebra underpins the way many Big Data algorithms and data structures are composed: full-text search can be viewed as doing matrix multiplication of the term-document matrix by the query vector (giving a vector over documents where the components are the relevance score), computing co-occurrences in a collaborative filtering context (people who viewed X also viewed Y, or ratings-based CF like the Netflix Prize contest) is taking the squaring the user-item interation matrix, calculating users who are k-degrees separated from each other in a social network or web-graph can be found by looking at the k-fold product of the graph adjacency matrix, and the list goes on (and these are all cases where the linear structure of the matrix is preserved!)</p>

<p>
Each of these examples deal with cases of matrices which tend to be tremendously large (often millions to tens of millions of rows or more, by sometimes a comparable number of columns), but also rather sparse.  Sparse matrices are nice in some respects: dense matrices which are 10<sup>7</sup> on a side would have 100 trillion non-zero entries!  But the sparsity is often problematic, because any given two rows (or columns) of the matrix may have zero overlap.  Additionally, any machine-learning work done on the data which comprises the rows has to deal with what is known as "the curse of dimensionality", and for example, there are too many columns to train most regression or classification problems on them independently.
</p>

<p>
One of the more useful approaches to dealing with such huge sparse data sets is the concept of <em>dimensionality reduction</em>, where a lower dimensional space of the original column (feature) space of your data is found / constructed, and your rows are mapped into that subspace (or submanifold).  In this reduced dimensional space, "important" components to distance between points are exaggurated, and unimportant ones washed away, and additionally, sparsity of your rows is traded for drastically reduced dimensional, but <em>dense</em> "signatures".  While this loss of sparsity can lead toits own complications, a proper dimensionality reduction can help reveal the most important features of your data, expose correlations among your supposedly independent original variables, and smooth over the zeroes in your correleation matrix.
</p>

<p>
One of the most straightforward techniques for dimensionality reduction is the matrix decomposition: singular value decomposition, eigen decomposition, non-negative matrix factorization, etc.  In their truncated form these decompositions are an excellent first approach toward linearity preserving unsupervised feature selection and dimensional reduction.  Of course, sparse matrices which don't fit in RAM need special treatment as far as decomposition is concerned.  Parallelizable and/or stream-oriented algorithms are needed.
</p>
<p>
Currently implemented: Singular Value Decomposition using the Asymmetric Generalized Hebbian Algorithm outlined in <a href="http://www.scribd.com/doc/7017586/Gorrell-Webb">Genevieve Gorrell &amp; Brandyn Webb's paper</a>  and there is a Lanczos implementation, both single-threaded, and in the contrib/hadoop subdirectory, as a hadoop map-reduce (series of) job(s).  Coming soon: <a href="http://arxiv.org/abs/0909.4061">stochastic decomposition</a>.
</p>

<p>This code is in the process of being absorbed into the <a href="http://lucene.apache.org/mahout">Apache Mahout Machine Learning Project</a>.  </p>

<?php require "../includes/footer.php" ?>
