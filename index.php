<?php
function findRoot() { return(substr($_SERVER["SCRIPT_FILENAME"], 0, (stripos($_SERVER["SCRIPT_FILENAME"], $_SERVER["SCRIPT_NAME"])+1))); }

include(findRoot() . 'run.php');

displayHead('Blog');

for ($i=(sizeof($posts)-1); 0 <= $i; $i--) {
	$posts[$i]->displayArticle();
}

displayFooter();
?>