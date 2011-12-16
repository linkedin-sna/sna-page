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
Examples
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

<?php require "../includes/footer.php" ?>
