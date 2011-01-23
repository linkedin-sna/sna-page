<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns:og="http://ogp.me/ns#">
	<head>
		<title><?= $PROJ_NAME ?></title>
		<link rel='stylesheet' href='styles.php' type='text/css'>
		<?php
			if(isset($PROJ_FAVICON_PATH) && $PROJ_FAVICON_PATH)
				echo "<link rel=\"icon\" type=\"${PROJ_FAVICON_MIME}\" href=\"${PROJ_FAVICON_PATH}\">";
			if(isset($PROJ_KEYWORDS) && $PROJ_KEYWORDS)
				echo "<meta name=\"keywords\" content=\"${PROJ_KEYWORDS}\">";
			if($PROJ_DESCRIPTION)
			    echo "<meta name=\"description\" content=\"${PROJ_DESCRIPTION}\">";
		?>
		<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />
		
		<meta property="og:title" value="${PROJ_NAME}" />
		<meta property="og:image" value="${PROJ_ICON_PATH}" />
		<meta property="og:description" value="${PROJ_DESCRIPTION}" />
		<meta property="og:site_name" value="SNA Projects" />
		<meta property="og:type" value="website" />
	</head>
	<body>
		<div>
			<div id="header" style="overflow: hidden">
				<div style='float: left; margin-right: 10px'>
					<?php 
					  if(isset($PROJ_ICON_PATH)) 
					    echo "<img src='${PROJ_ICON_PATH}'>";
					?>
				</div>
				<div>
					<div class="title"><?= $PROJ_NAME ?></div>
					<div class="subtitle"><?= $PROJ_SUBTITLE ?></div>
					<div class="projects"><?php require "project_list.php";?></div>
				</div>
			</div>
	<div class="lsidebar">
		<ul>
		<?php
		if(isset($PROJ_NAV_LINKS)) {
			foreach ($PROJ_NAV_LINKS as $desc => $link) {
				echo "<li>
					      <a href=\"${link}\">${desc}</a>
				      </li>";
			}
		}
		?>
		</ul>
	
		<?php
		if (isset($PROJ_HTML)){
		  echo "$PROJ_HTML";
		}
		?>
	</div>
	
	<div class='content'>
	