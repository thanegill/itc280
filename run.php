<?php
include_once('config.php');

//Blocks IE from accessing site and redirects to "/ie_sukcs.html"
if (IE_BUMP && (eregi("MSIE",getenv("HTTP_USER_AGENT")) || eregi("Internet Explorer",getenv("HTTP_USER_AGENT")))) {
	Header('Location: ' . IE_BUMP_LOCATION);
	exit;
}
if (!function_exists('findRoot')) {
	function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }
}

//Include necessaries
include(findRoot() . 'includes/theme.php');
include(findRoot() . 'includes/markdown.php');
include(findRoot() . 'includes/Post.php');

//Get posts
$posts = getPosts(findRoot() . ARTICLE_FOLDER);

//Get theme if set
if (isset($_COOKIE['theme'])) {
    define('THEME_FOLDER', $_COOKIE['theme']);
} else {
    define('THEME_FOLDER', DEFAULT_THEME);
}

?>