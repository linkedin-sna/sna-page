<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>Documentation</h1>

This documentation assumes you have browsed through the <a href="quickstart.php">Quick Start</a> guide to learn the basics.

<h2>Jobs and Configuration</h2>

<p>
Azkaban jobs are basically code plus configuration values. Configuration is stored as a properties file in the format <code>key=value</code>. These job files can be created manually in a text editor or through the web interface. Many of the configuration parameters will be custom configurations for your job, but there are a number of standard parameters that activate common job functionality. These parameters are described in the following sections.
</p>

<h3>Job Types</h3>

All jobs require a <code>type</code> property specifying how to execute them. Currently, there are four job types: <code>java</code>, <code>command</code>, <code>javaprocess</code>, and <code>pig</code>.

<table class="props-table">
  <tr>
    <th>Property</th>
    <th>Required?</th>
    <th>Meaning</th>
  </tr>
  <tr>
    <td><code>type</code></td>
    <td>required</td>
    <td>The job type: <code>java</code>, <code>command</code>, <code>javaprocess</code>, or <code>pig</code></td>
  </tr>
</table>

<p>
Each of these types has a variety of options as described in the following sections.
</p>

<h4>command jobs</h4>

<p>
Command jobs are essentially Unix commands executed as separate processes. Any output sent to standard out or standard error is redirected to the Azkaban log for the job. The job is considered to have succeeded if it completes with an exit code of zero. A non-zero exit code is treated as a failure.
</p>

<p>
The following properties are available in <code>command</code> jobs:
</p>

<table class="props-table">
  <tr>
    <th>Property</th>
    <th>Required?</th>
    <th>Meaning</th>
    <th>Example</th>
  </tr>
  <tr>
    <td><code>command</code></td>
    <td>required</td>
    <td>Specifies the command to execute.</td>
    <td><code>ls -lh</code></td>
  </tr>
  <tr>
    <td><code>command.<i>n</i></code></td>
    <td>optional</td>
    <td>Defines additional commands that are run sequentially after <code>command</code>.</td>
    <td><code>ls -lh</code></td>
  </tr>
  <tr>
    <td><code>working.dir</code></td>
    <td>optional</td>
    <td>Specifies the directory in which the command is invoked. The default working directory is the job's directory.</td>
    <td><code>/home/ejk</code></td>
  </tr>
  <tr>
    <td><code>env.<i>property</i></code></td>
    <td>optional</td>
    <td>Specifies environment variables that should be set before running the command. <i>property</i> defines the name of the environment variable, so env.VAR_NAME=VALUE creates an environment variable $VAR_NAME and gives it the value of VALUE.</td>
    <td><code></code></td>
  </tr>
<table>

<h4>javaprocess jobs</h4>
<p>
Java process jobs are a convenient wrapper for kicking off Java-based programs. It is equivalent to running a class with a main method from the command line. The following properties are available in <code>javaprocess</code> jobs:
</p>
<table class="props-table">
  <tr>
    <th>Property</th>
    <th>Required?</th>
    <th>Meaning</th>
    <th>Example</th>
  </tr>
  <tr>
    <td><code>java.class</code></td>
    <td>required</td>
    <td>The class that contains the main function.</td>
    <td><code>azkaban.example.test.HelloWorld</code></td>
  </tr>
  <tr>
    <td><code>classpath</code></td>
    <td>optional</td>
    <td>A comma-delimited list of JAR files and directories to be added to the classpath. If not set, it adds all JARs in the working directory to the classpath.</td>
    <td><code>commons-io.jar,helloworld.jar</code></td>
  </tr>
  <tr>
    <td><code>Xms</code></td>
    <td>optional</td>
    <td>The initial memory pool size to start the JVM. The default is 64M.</td>
    <td><code>64M</code></td>
  </tr>
  <tr>
    <td><code>Xmx</code></td>
    <td>optional</td>
    <td>The maximum memory pool size. The default is 256M.</td>
    <td><code>256M</code></td>
  </tr>
  <tr>
    <td><code>main.args</code></td>
    <td>optional</td>
    <td>List of comma-delimited arguments to pass to the Java main function.</td>
    <td><code>arg1,arg2</code></td>
  </tr>
  <tr>
    <td><code>jvm.args</code></td>
    <td>optional</td>
    <td>Arguments set for the JVM. This is not a list. The entire string is passed intact as a VM argument.</td>
    <td><code>-Dmyprop=test -Dhello=world</code></td>
  </tr>
  <tr>
    <td><code>working.dir</code></td>
    <td>optional</td>
    <td>Inherited from <code>command</code> jobs.</td>
    <td><code>/home/ejk</code></td>
  </tr>
  <tr>
    <td><code>env.<i>property</i></code></td>
    <td>optional</td>
    <td>Inherited from <code>command</code> jobs.</td>
    <td><code>env.MY_ENV_VARIABLE=testVariable</code></td>
  </tr>
</table>

<h4>pig jobs</h4>
<p>
This job type runs pig scripts through grunt. The following properties are available in <code>pig</code> jobs:
</p>
<table class="props-table">
  <tr>
    <th>Property</th>
    <th>Required?</th>
    <th>Meaning</th>
    <th>Example</th>
  </tr>
  <tr>
    <td><code>pig.script</code></td>
    <td>optional</td>
    <td>Specifies the pig script to run. If not set, it uses the job name to find <code><i>jobname</i>.pig</code>.</td>
    <td><code>pig-example.pig</code></td>
  </tr>
   <tr>
    <td><code>udf.import.list</code></td>
    <td>optional</td>
    <td>Comma-delimited list of UDF imports</td>
    <td><code>oink.,linkedin.udf.</code></td>
  </tr>
  <tr>
    <td><code>param.<i>name</i></code></td>
    <td>optional</td>
    <td>Used for parameter replacement to pass parameters from your job into your pig script. Order is not guaranteed. See the <a href="http://hadoop.apache.org/pig/docs/r0.6.0/piglatin_ref2.html#Parameter+Substitution">pig documentation</a> for information on using pig parameters in your scripts.</td>
    <td><code>param.variable1=myvalue</code></td>
  </tr>
  <tr>
    <td><code>paramfile</code></td>
    <td>optional</td>
    <td>Comma-delimited list of files used for variable replacement in your pig script. Order is not guaranteed, and param.<i>name</i> takes precedence.</td>
    <td><code>paramfile1,paramfile2</code></td>
  </tr>
  <tr>
    <td><code>hadoop.job.ugi</code></td>
    <td>optional</td>
    <td>Sets the user name and group for Hadoop jobs.</td>
    <td><code>hadoop,group</code></td>
  </tr>
  <tr>
    <td><code>classpath</code></td>
    <td>optional</td>
    <td>Inherited from <code>javaprocess</code> jobs.</td>
    <td><code>commons-io.jar,helloworld.jar</code></td>
  </tr>
  <tr>
    <td><code>Xms</code></td>
    <td>optional</td>
    <td>Inherited from <code>javaprocess</code> jobs.</td>
    <td><code>64M</code></td>
  </tr>
  <tr>
    <td><code>Xmx</code></td>
    <td>optional</td>
    <td>Inherited from <code>javaprocess</code> jobs.</td>
    <td><code>256M</code></td>
  </tr>
  <tr>
    <td><code>jvm.args</code></td>
    <td>optional</td>
    <td>Inherited from <code>javaprocess</code> jobs.</td>
    <td><code>-Dmyprop=test -Dhello=world</code></td>
  </tr>
  <tr>
    <td><code>working.dir</code></td>
    <td>optional</td>
    <td>Inherited from <code>command</code> jobs.</td>
    <td><code>/home/ejk</code></td>
  </tr>
  <tr>
    <td><code>env.<i>property</i></code></td>
    <td>optional</td>
    <td>Inherited from <code>command</code> jobs.</td>
    <td><code></code></td>
  </tr>
</table>

<h4>java jobs</h4>

<p>
Java jobs are any Java classes that have a <code>run()</code> method, such as a <code><a href="http://java.sun.com/j2se/1.5.0/docs/api/java/lang/Runnable.html">java.lang.Runnable</a></code>. To avoid tying your code to framework-specific interfaces, the Java class does not need to implement any interface; however, Azkaban can make use of all the methods given in the following class (some of which are optional):
</p>

<img src="images/example_job.png">

<p>
Logging should be to a log4j logger with the logger name set to the job name. Azkaban provides a log4j appender that sends these messages to the appropriate job log.
</p>

<table class="props-table">
  <tr>
    <th>Property</th>
    <th>Required?</th>
    <th>Meaning</th>
    <th>Default</th>
  </tr>
  <tr>
    <td><code>job.class</code></td>
    <td>required</td>
    <td>The Java class to run</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><code>method.run</code></td>
    <td>optional</td>
    <td>The name of the no-arg method to use for running the job</td>
    <td><code>run</code></td>
  </tr>
  <tr>
    <td><code>method.cancel</code></td>
    <td>optional</td>
    <td>The name of the no-arg method to cancel the job</td>
    <td><code>cancel</code></td>
  </tr>
  <tr>
    <td><code>method.progress</code></td>
    <td>optional</td>
    <td>The name of the no-arg method to use for getting progress from the job</td>
    <td><code>getProgress</code></td>
  </tr>
<table>

<h3>Job Locking</h3>

<p>
There are three types of locks in Azkaban: <b>permit</b>, <b>read.lock</b>, and <b>write.lock</b>.
</p>

<h4>Permits</h4>
<p>Permit locks are locks used to throttle concurrent access to a resource. For example if you want to guarantee that no more than 4 jobs ever read from a particular database at once, you could set up a pool of 4 permits and have each job require one permit to run. The number of permits are set using the <code>total.job.permits</code> parameter in a job directory's <code>.property</code> file.
</p>
<p>
The number of permits the job must acquire to run is provided in the job parameters by <code>job.permits</code>. All permits are immediately released when the job finishes or fails.
</p>

<h4>Read and Write Locks</h4>
<p>
Azkaban support named <a href="http://en.wikipedia.org/wiki/Readers-writer_lock">Read/Write locks</a> for resources. A common use case is locking access to a file in HDFS for modification&mdash;for example, when you have many jobs that read a file and one that recreates it, you want to ensure you do not recreate the file while others are reading it. Readers do not block other readers and any number of readers are allowed; however, only a single writer is allowed, and to begin writing all readers currently executing must complete. 
</p>
<p>
These locks can be set through the <code>read.lock</code> and <code>write.lock</code> parameters as defined in the following table.
</p>

<table class="props-table">
  <tr>
    <th>Property</th>
    <th>Meaning</th>
    <th>Example</th>
  </tr>
<tr>
  <td><code>job.permits</code></td>
  <td>Used to throttle the number of jobs using a particular resource. See the previous locking section.</td>
  <td>3</td>
</tr>
<tr>
  <td><code>read.lock</code></td>
  <td>Comma-separated list of resource locks. Used to obtain a read lock on the named resource. See the previous locking section.</td>
  <td>/some/resource/name1,/some/resource/name2</td>
</tr>
<tr>
  <td><code>write.lock</code></td>
  <td>Comma-separated list of resource locks. Used to obtain a write lock on the named resource. See the previous locking section.</td>
  <td>/some/resource/name1,/some/resource/name2</td>
</tr>
</table>

<h3>Job Directory Layout</h3>

Jobs files are property files that end in <code>.job</code>. Additional properties can be given in <code>.properties</code> files. A property can refer to other properties such as in the following example:
<pre>
db.url=${db.host}:${db.port}
</pre>
<p>
A common need is to support deploying a single job in many environments (for example, Dev, QA, and Production) and each of these environments has some difference that requires special configuration. To allow this, Azkaban makes all the configuration for a job hierarchical. A job inherits any properties defined in the local directory to which it is deployed, or if the property is not found there, then in the parent directories. To avoid adding any environment-specific properties to the job (such as a particular host name or port), use a variable such as <code>${some.url}</code>, which is defined in a global properties file. This global properties file can be set in each environment the job needs to run in, and not redeployed with the job.
</p>
	
<h3>Other Standard Job Properties</h3>
<p>
A number of properties are made available to jobs of all types by the framework. These can be set by adding the given property to any job. The following table lists the available properties and their meanings.
</p>
<table class="props-table">
  <tr>
    <th>Property</th>
    <th>Meaning</th>
    <th>Example</th>
  </tr>
  <tr>
    <td><code>dependencies</code></td>
    <td>A comma-separated list of job names, one for each job depended on. Dependencies are always run first, and a job is only started if all its dependencies complete successfully.</td>
    <td>foo, bar</td>
  </tr>
  <tr>
    <td><code>success.emails</code></td>
    <td>A comma-separated list of email addresses to notify upon the success of the job</td>
    <td>gwb@whitehouse.gov, barryo@whitehouse.gov</td>
  </tr>
  <tr>
    <td><code>failure.emails</code></td>
    <td>A comma-separated list of email addresses to notify upon the failure of the job</td>
    <td>gwb@whitehouse.gov, barryo@whitehouse.gov</td>
  </tr>
  <tr>
    <td><code>retries</code></td>
    <td>If your job fails, this property instructs Azkaban to run the job again up to the number of retries given. This is useful if you have a job that is unreliable due to circumstances outside your control, and simply trying again is likely to help.
	</td>
    <td>3</td>
  </tr>
  <tr>
    <td><code>retry.backoff</code></td>
    <td>The time to wait in between attempts when retries is set to a positive number (see <code>retries</code> property). The job waits for this many milliseconds between attempts.</td>
    <td>30000</td>
  </tr>
<table>

<h3>Azkaban System Properties</h3>

The following table lists the system-wide properties that can be set for Azkaban itself.

<table class="props-table">
  <tr>
    <th>Property</th>
    <th>Meaning</th>
    <th>Example</th>
  </tr>
  <tr>
    <td><code>mail.host</code></td>
    <td>The hostname of the mail server to which email notifications are sent.</td>
    <td>localhost</td>
  </tr>
  <tr>
    <td><code>mail.user</code></td>
    <td>The user name on the mail server.</td>
    <td>joebob</td>
  </tr>
  <tr>
    <td><code>mail.password</code></td>
    <td>The password of the mail server.</td>
    <td>password</td>
  </tr>
  <tr>
    <td><code>scheduler.threads</code></td>
    <td>The maximum number of threads that can be used for running jobs.</td>
    <td>50</td>
  </tr>
  <tr>
    <td><code>total.job.permits</code></td>
    <td>A number of permits available in the system for assignment to jobs that set the <code>job.permits</code> property.</td>
    <td>50</td>
  </tr>
</table>

<?php require "../includes/footer.php" ?>