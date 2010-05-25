<?php header("Content-type: text/css"); ?>

html, body{
	font-family:Arial,sans-serif;
    margin: 0px;
	padding: 0px;
	background-color: #fff;
	color: #222;
	line-height: 150%;
	font-size: 11pt;
}
code, pre {
	font: 1em/normal "courier new", courier, monospace;
}
h1, h2, h3, h4 {
  color: <?= $PROJ_PRIMARY_COLOR ?>;
}
a {
	color: <?= $PROJ_PRIMARY_COLOR ?>;
	text-decoration: none;
}
#header {
	xmargin: 3em -2em 1em 0;
	padding: 1.2em 0 1.2em 3em;
	border-width: 0px;
	background-color: <?= $PROJ_PRIMARY_COLOR ?>;
	xwidth: 80%;
	min-width: 900px;
}
.title {
	color: white;
	font-size: 24pt;
	margin: 2px;
}
.subtitle, .projects, .projects a {
	color: white;
	font-size: 14pt;
	font-style: italic;
	margin: 2px;
	line-height: 1.5;
}
.projects, .projects a {
	font-style: normal;
	font-size: 11pt;
}
.lsidebar {
	float: left;
	font-size: 12pt;
	color: <?= $PROJ_PRIMARY_COLOR ?>;
	width: 150px;
}
.lsidebar li {
	list-style-type: none;
}
.lsidebar li a {
	text-decoration: none;
	color: <?= $PROJ_PRIMARY_COLOR ?>;
}
.content {
	width: 700px;
	margin-left: 200px;
	xpadding: 10px;
	min-height: 800px;
}
.numeric {
  text-align: right;
}
.data-table {
  border: 1px solid #a9a9a9;
  border-collapse: collapse;
}
.data-table td, .data-table th {
  border: 1px solid #888;
  padding: 2px;
}
.data-table th {
  background-color: #ccc;
  font-weight: bold;
}
.advert-message {
  border: 3px solid <?= $PROJ_PRIMARY_COLOR ?>;
  padding: 15px;
}