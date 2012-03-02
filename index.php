<?php
function findRoot() { return(substr($_SERVER["SCRIPT_FILENAME"], 0, (stripos($_SERVER["SCRIPT_FILENAME"], $_SERVER["SCRIPT_NAME"])+1))); }

include(findRoot() . 'run.php');

displayHead('Blog');

for ($i=(sizeof($posts)-1); 0 <= $i; $i--) {
	$posts[$i]->displayArticle();
}

displayPager();

function displayPager() {
	global $posts;
	echor('<div id="pagger"><ul>');
	
	$numOfPages = sizeof($posts) / 4 ;
	echo($numOfPages);
	
	echor('</ul></div>');
}

displayFooter();
?>