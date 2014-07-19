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

define('WEB_ROOT', findRoot());

//Get theme if set
if (isset($_COOKIE['theme']) && !empty($_COOKIE['theme'])) {
    define('THEME_FOLDER', WEB_ROOT . 'themes/' . $_COOKIE['theme']);
} else {
    define('THEME_FOLDER', WEB_ROOT . 'themes/' . DEFAULT_THEME);
}

//Include necessaries
include(WEB_ROOT . 'includes/theme.php');
include(WEB_ROOT . 'includes/markdown.php');
include(WEB_ROOT . 'includes/Post.php');

//Get Articles
$articles = getArticles(findRoot() . ARTICLE_FOLDER);


?>