<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<p>
Azkaban is simple batch scheduler for constructing and running Hadoop jobs or other offline processes.
</p>

<h2>What is it?</h2>

<p>
A batch job scheduler can be seen as a combination of the <code>cron</code> and <code>make</code> Unix utilities. Batch jobs need to be scheduled to run periodically. They also typically have intricate dependency chains&mdash;for example, dependencies on various data extraction processes or previous steps. Larger processes might have 50 or 60 steps, of which some might run in parallel and others must wait for one another. Combining all these processes into a single program allows you to control the dependency management, but can lead to sprawling monolithic programs that are difficult to test or maintain. Simply scheduling the individual pieces to run at different times avoids the monolithic problem, but introduces many timing assumptions that are inevitably broken. Azkaban is a <i>workflow</i> scheduler that allows the pieces to be declaratively assembled into a single workflow, and for that workflow to be scheduled to run periodically.
</p>

<p>
A good batch workflow system allows a program to be built out of small reusable pieces that need not know about one another. By declaring dependencies, you can control sequencing. Other functionality available from Azkaban can then be layered on top of the job&mdash;email notifications of success or failure, resource locking, retry on failure, log collection, historical job runtime information, and so on. 	
</p>

<h2>Why was it made?</h2>

<p>
Schedulers are readily available (both open source and commercial), but tend to be extremely unfriendly to work with&mdash;they are basically bad graphical user interfaces grafted onto 20-year old command-line clients. We wanted something that made it reasonably easy to visualize job hierarchies and run times without the pain. Previous experience made it clear that a good batch programming framework can make batch programming easy and successful in the same way that a web framework can aid web development beyond what you can do with an HTTP library and sockets.
</p>

<h2>News</h2>
<h4>Nov 22, 2010: Azkaban Version 0.06 Released.</h4>
New to This release:
<ul>
<li>New UI Style</li>
<li>AJAX for job list for reduced page load time</li>
<li>New Flow View for selective execution and restarts</li>
<li>More job type support (python, ruby)</li>
</ul>
<p>Get it <a style="color:#003399" href="https://github.com/azkaban/azkaban/downloads">here</a>.</p>

<h2>State of the project</h2>

<p>
We have been using Azkaban internally at <a href="http://www.linkedin.com">LinkedIn</a> for since early 2009, and have about a hundred jobs running in it, mostly Hadoop jobs or ETL of some type. Azkaban is quite new as an open source project though, and we are working now to generalize it enough to make it useful for everyone.
</p>
<p>
Any patches, bug reports, or feature ideas are quite welcome. We have created a <a href="http://groups.google.com/group/azkaban-dev">mailing list</a> for this purpose.
</p>

<?php require "../includes/footer.php" ?>