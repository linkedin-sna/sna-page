<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<h1>
Quickstart
</h1>

<p>
This quickstart requires a Cleo distribution of version 1.2.0 or above.
The <code>src/examples</code> directory contains a number of classes such as
<code>GenericCompanyTypeahead</code> and <code>MyFriendsTypeahead</code>.
These example classes demonstrate how to develop and configure applications to perform
generic or network typeahead search.
</p>
	
<h3>
Code Examples
</h3>

<ul>
  <li><a href="tutorial_CompanyTypeahead.php">CompanyTypeahead</a></li>
  
  <p>
  This example shows how to configure Cleo to perform typeahead search over companies in absence of social network.
  It provides a reference implementation for generic typeahead search over relatively large data sets.
  It can be adapted to support varying domains such as companies, groups, magazines and so on.
  </p>
  
  <li><a href="tutorial_MyFriendsTypeahead.php">MyFriendsTypeahead</a></li>
  <p>
  This example shows how to configure Cleo to perform typeahead search over the social network of friends.
  </p>
  
</ul>

<h3>
Restful WebApp
</h3>

<p>
The <a href="https://github.com/jingwei/cleo-primer">cleo-primer</a> package provides a basic RESTful implementation of partial, out-of-order and real-time typeahead and autocomplete services.
</p>

<p>
You can follow the steps below to set up a typeahead service for public companies listed at Nasdaq.
</p>

<ol>
  <li>Download <a href="https://github.com/jingwei/cleo-primer">cleo-primer</a> from Github.</li>
  <pre>
  git clone --depth 1 https://github.com/jingwei/cleo-primer.git cleo-primer
  </pre>

  <li>Launch the cleo-primer web application from the cleo-primer folder.</li>
  <pre>
  MAVEN_OPTS="-Xms1g -Xmx1g" mvn jetty:run \
  -Dcleo.instance.name=Company \
  -Dcleo.instance.type=cleo.primer.GenericTypeaheadInstance \
  -Dcleo.instance.conf=src/main/resources/config/generic-typeahead
  </pre>
  <p>
  You can customize the web application by choosing different values for <code>cleo.instance.name</code>, <code>cleo.instance.type</code>, and <code>cleo.instance.conf</code>. Depending on the size of your data sets, you may need to specify a different JVM heap size.
  </p>

  <p>
  <ul>
    <li>The <code>cleo.instance.name</code> is assigned <code>Company</code> because we are building a typeahead service for companies listed at Nasdaq.</li>
    <li>The <code>cleo.instance.type</code> specifies a Java class <code>cleo.primer.GenericTypeaheadInstance</code> that instantiates an instance of Generic Typeahead. This is the only instance type supported by cleo-primer at the present time. We will add more in the coming future.</li>
    <li>The <code>cleo.instance.conf</code> specifies a file directory where several generic typeahead configuration files are located. For more information on configuring Generic Typeahead and Network Typeahead, please refer to the code examples listed above.</li>
  </ul>
  </p>

  <li>Index Nasdaq public companies using the prepared XML file.</li>
  <pre>
  ./scripts/post-element-list.sh dat/nasdaq-company-list.xml
  </pre>
  <p>
  If you have a different type of elements such as schools and publications, you need to prepare your XML file according to
  <a href="https://github.com/jingwei/cleo-primer/blob/master/src/main/java/cleo/primer/rest/model/ElementDTO.java">cleo.primer.rest.model.ElementDTO</a> 
  </p>

  <li>Visit the URL below to try out cleo-primer.</li>
  <pre>
  http://localhost:8080/cleo-primer
  </pre>
</ol>

<?php require "../includes/footer.php" ?>
