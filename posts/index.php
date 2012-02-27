<?php
function findRoot() { return(substr($_SERVER["SCRIPT_FILENAME"], 0, (stripos($_SERVER["SCRIPT_FILENAME"], $_SERVER["SCRIPT_NAME"])+1))); }

include(findRoot() . 'run.php');

if (!empty($_GET['p']) || isset($_GET['p'])) {

	displayPost($_GET['p']);
	
} else if (!empty($_GET['t']) || isset($_GET['t'])) {

	displayType($_GET['t']);
	
} else if (!empty($_GET['post']) || isset($_GET['post'])) {
	//Old POST type
	displayPost($_GET['post']);
	
} else if (!empty($_GET['type']) || isset($_GET['type'])) {
	//Old POST type
	displayType($_GET['type']);
	
} else {

	display404Error();
	
}

function displayPost($postLink) {
	global $posts;
	$error = true;
	
	for ($j=(sizeof($posts)-1); 0 <= $j; $j--) {
		if ($posts[$j]->getLink() == $postLink) {
			displayHead($posts[$j]->GetFullTitle());
			$posts[$j]->displayArticle();
			$posts[$j]->displayComments();
			displayCommentForm();
			$error = false; //trips error to false if article found
		}
	}
	
	//article not found
	if ($error) {
		display404Error();
	}
}

function displayType($type) {
	global $posts;
	
	//Assigns the pageTitle
	if ($type == 'assignment') {
		displayHead('Assignment Posts');
	} elseif ($type == 'extracredit') {
		displayHead('Extra Credit Posts');
	} elseif ($type == 'resource') {
		displayHead('Resource Posts');
	}
	
	if ($type == 'assignment' || $type == 'extracredit' || $type == 'resource') {
		for ($j=(sizeof($posts)-1); 0 <= $j; $j--) {
			if ($posts[$j]->getType() == $type) {
				$posts[$j]->displayArticle();
			}
		}
	} else {
		display404Error();
	}
}

function display404Error() {
	displayHead('Error 404 - No Post');
	echo('<article>
		<h1>404 Article Doesnt Exsist</h1>
		<h3><a href="/">Back Home</a></h3>
		</article>');
}

displayFooter();

?>