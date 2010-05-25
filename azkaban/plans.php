<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h2>Plans</h2>

<p>
This page collects a few things we hope to work on soon but haven't started yet. If you are interested in working on one of these or have thoughts on how it should work, send an email to the <a href="http://groups.google.com/group/azkaban-dev">mailing list</a>.
</p>

<h3>UI Improvements</h3>

The current jQuery javascript tree is quite slow, and having a single tree with all the jobs is simply TMI. 

<h3>Flow Display</h3>

You should be able to display job flows as full DAGs. SVG is an easy way to draw these graphs, but requires doing the layout of the nodes yourself. But hey, graph layout is fun.

<h3>Authentication</h3>

Currently we have none.

<h3>Gantt Chart for Flow Execution</h3>

Optimizing a flow of partially parallel, partially sequential jobs can be quite difficult. Having a simple visualization of which jobs are taking the longest and blocking everything else can help enormously.

<h3>Time estimates for Flows</h3>

We already track the past execution times for jobs, it would be good to roll up the past estimates into a projection (say a moving avg of the last few runs), and by doing this for each job in the flow be able to give better % complete and projected completion time information.

<h3>Core improvements</h3>

Refactor the overall job manager and graph execution layer a bit.

<h3>REST interface</h3>

We should add rest interfaces for the main job control functionality to make scripting easier.

<h3>Fix or kill HDFS viewer</h3>

We have a HDFS viewer that is a little prettier but missing a lot of the functionality of Hadoop's. It does allow per-filetype plugins. 

<?php require "../includes/footer.php" ?>