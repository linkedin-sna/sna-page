<?php
if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
    header("Content-type: application/xhtml+xml");
    echo ("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n");
    }
else { header("Content-type: text/html"); }
?> 

Hello

<svg id="graph" height="500px" width="500px" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 100% 100%" >
</svg>

