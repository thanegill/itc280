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
	global $posts, $ARTICLE_TYPES;
	
	if(in_array($type, $ARTICLE_TYPES)) {
		//find key of type
		$key = array_search($type, $ARTICLE_TYPES);
		
		displayHead($key);
		
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