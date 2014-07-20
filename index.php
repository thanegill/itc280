<?php
function findRoot() { return(substr($_SERVER["SCRIPT_FILENAME"], 0, (stripos($_SERVER["SCRIPT_FILENAME"], $_SERVER["SCRIPT_NAME"])+1))); }

include(findRoot() . 'run.php');

if (!empty($_GET['a']) && isset($_GET['a'])) {
	//Article
	displayPost($_GET['a']);
	
} else if (!empty($_GET['t']) && isset($_GET['t'])) {
	//Type
	displayType($_GET['t']);
	
} else {
	//display all
	displayType('all');
}

displayFooter();

?>