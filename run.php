<?php
//Blocks IE from accessing site and redirects to "/ie_sukcs.html"
if (eregi("MSIE",getenv("HTTP_USER_AGENT")) || eregi("Internet Explorer",getenv("HTTP_USER_AGENT"))) {
	Header("Location: /ie_sucks.html");
	exit;
}
if (!function_exists('findRoot')) {
	function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }
}

include_once(findRoot() . 'config.php');
include_once(findRoot() . 'includes/markdown.php');
include_once(findRoot() . 'includes/util.php');
include_once(findRoot() . 'includes/Post.php');
include_once(findRoot() . 'includes/theme.php');

//Get posts
$posts = getPosts(findRoot() . 'posts_content');

//Get theme
if (isset($_COOKIE["theme"])) {
    define('THEME_FOLDER', $_COOKIE["theme"]);
} else {
    define('THEME_FOLDER', DEFAULT_THEME);
}

?>
