<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>Quick Start</h1>

<h2>Getting Azkaban</h2>
<p>
<a href="http://github.com/azkaban/azkaban/downloads">Download the latest release</a>, and unzip (or untar) it. The following instructions are relative to the directory in which you unzip the file.
</p>

<h4>Standalone Deployment</h4>

Run Azkaban from the command line by issuing the following command:

<pre>
  > mkdir /some-dir/azkaban-jobs
  > bin/azkaban-server.sh --job-dir /some_dir/azkaban-jobs
</pre>

You can then navigate to <a href="http://localhost:8081">http://localhost:8081</a> in your browser to interact with the web user interface.

<h4>Deployment in Tomcat</h4>

There is a prebuilt war file in the <code>dist/</code> directory of the release. This file can be deployed using standard means in Tomcat or any other servlet container. In this case, the job directory must be set with the $AZKABAN_HOME environment variable.

<p>
Whether it is run through Tomcat or from the command line, the following screenshot displays the index page.
</p>

<img src="images/index-page.png">

<h2>Jobs</h2>

<h3>Creating a simple job</h3>

<p>A job is a process you want to run in Azkaban. It can be kicked off from the user interface, scheduled to run in the future, or used as a dependency for other jobs. Each time Azkaban executes your job it records whether it succeeded or failed, how long it ran, and any logging it produced for future reference.</p>

<p>
An Azkaban job is a file ending in the suffix <code>.job</code> that appears in your job directory. For example a file <code>foo.job</code> declares a job named foo. The properties of the job are set in the job file using the form <code>key=value</code>. Here are the contents of an example job file:
</p>

<pre>
  # This is a comment
  type=command
  command=echo "Hello World"
</pre>

<p>
This job has two properties: <code>type</code> and <code>command</code>. The <code>type</code> property is required of all jobs and determines how the job is to be run&mdash;in this case as a simple Unix command. The <code>command</code> property is specific to Unix jobs and gives the Unix command line to be executed. The job could also have additional properties for its own use.
</p>

<p>
Creating a job can be done from the user interface, as well by clicking <b>Create Job</b> on the main page. The next sections describe how to create and bundle a complete job flow. The sections also provide additional details on the various types of jobs and the available standard properties.
</p>

<h3>Creating a job flow</h3>

A job flow is a set of jobs that depend on one another. The dependencies of a job always run before the job itself can run. To add dependencies to a job, add the <code>dependencies</code> property as shown in the following example.

<pre>
$ cat > foo.job
type=command
command=echo foo
$ cat > bar.job
type=command
dependencies=foo
command=echo bar
</pre>

<h3>Deploying a job flow</h3>

<p>
Directly editing the job files works when developing a job flow on your desktop, but when it is ready to be deployed in production you might want to wrap everything up and ship it around. To support this, Azkaban supports the deployment of <code>.zip</code> files containing a set of jobs, additional configuration, JAR files, and any other artifacts needed. The following example shows how to create such a zip file.
<p>
	
<p>
Add these files to a zip file, such as <code>foobar.zip</code>. Note that the path will automatically filled with the zip name. The path determines the path in Azkaban to install the zip. Installing the zip to a pre-existing path will overwrite the existing installed zip.
<pre>
zip -u foobar.zip *.job
</pre>
<img src="images/upload-page.png">
</p>

<p>
This should now display in the user interface as the following hierarchy:
</p>
<p><img src="images/index-foobar.png"></p>
<p>
'bar' is a dependency of 'foo' and both are under 'foobar' section, which refers to the installed path. Jobs which are not dependencies of other jobs will appear as roots of the job 'tree'.
</p>
<p>
Now executing the job <code>bar</code> first executes <code>foo</code>. If <code>foo</code> completes successfully <code>bar</code> runs; otherwise, it is considered failed.
</p>

<h3>Running your job</h3>

<p>
Because we have made these changes within the job directory, they are automatically "deployed" and ready to be run. This can be done by checking the correct job in the user interface, and selecting <b>Run</b> (to run it immediately) or <b>Schedule</b> (to run it in the future). Scheduled jobs can be set to repeat on some predetermined schedule.
</p>

<p>
If you do not want to use a graphical user interface, you can run your job flow from the command line (or from within an IDE for debugging). To run a job named <code>my-job</code> stored in the root job directory <code>/some-dir/azkaban-jobs</code>, issue the following command:

<pre>
$ bin/run-job.sh --job-dir /some-dir/azkaban-jobs my-job
</pre>

<h3>Viewing a Job</h3>
<p>Selecting a job takes you to the Job Details page. From this page, job properties can be redefined or new jobs can be created. You can also view the history of job execution logs and job runtimes.</p>
<img src="images/job-details.png">

<h3>Flows</h3>
<p>Azkaban can display your dependency tree. There are two ways to do this. Hover over a job to see the View Flow link, or clicking on View/Restart link on the history page. </p>
<p>Use the mouse to move nodes and pan the graph, and the mouse wheel and the zoom bar to zoom in and out. Right clicking on the nodes allows you to disable nodes. Disable nodes appear faded out, and act as no-op jobs: they will 'run' but do nothing.</p>
<img src="images/flow_view.png">
<p>Pressing on restart in the history view will color the nodes depending on its status. Red for failed, green for success, blue for running (or waiting to run), and grey for ready. Clicking on Execute will run the flow immediately, and will run all jobs that aren't disabled. The above image represents a failed flow with the failure status trickling down to 'commmand_ls'. 'java_sleep' is disabled so clicking on Execute will re-run the flow but java_sleep will do nothing.</p>


<h3>Hierarchical Configuration</h3>

<p>
This simple method of storing job properties in <code>.job</code> files works, but it is important to be able to handle shared properties such as a database connection URL or the default notification email for job failure. To support this, Azkaban allows for properties to be separated into shared <code>.properties</code> files. Additionally, jobs inherit any properties in the job's parent directories, allowing for simple namespacing.
</p>
<p>
Consider the following example job directory layout:
</p>

<pre>
  system.properties
  baz.job
  my_flow/
    my_flow.properties
    foo.job
    bar.job
</pre>

<p>
There are three jobs declared here: <code>baz</code>, <code>foo</code>, and <code>bar</code>. Both <code>foo</code> and <code>bar</code> are run with the properties they declare, plus the properties defined in <i>both</i> properties files. However, <code>baz</code> only has access to the properties defined in its own job file and in <code>system.properties</code>.
</p>

<p>
Hierarchical configuration allows many people to build and deploy job flows to separate deployment paths totally independently, but to still share some common top-level configuration parameters. The hierarchy can be multiple levels, allowing a single workflow to have isolated sub-parts.
</p>
	
<h3>Additional Information</h3>

<p>
Additional information on job types, scheduling jobs, alerting, and so on can be found in the full <a href="documentation.php">documentation</a> section.	
</p>