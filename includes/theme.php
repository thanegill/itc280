<?php
function displayHead($title = '') {
	global $posts;
	$pageTitle = $title;
	include(findRoot() . 'themes/' .  THEME_FOLDER . '/head.php');
	
}

function displayCSS() {
	$cssFiles = getFiles(findRoot() . 'themes/' . THEME_FOLDER . '/' . CSS_FOLDER);
	for ($i = 0; $i < sizeof($cssFiles); $i++) {
		$cssFiles[$i] = substr($cssFiles[$i], strlen(findRoot())-1, strlen($cssFiles[$i]));
		echo('<link rel="stylesheet" href="' . $cssFiles[$i] . '" type="text/css"/>');
	}
}

//TODO
//function displayJS() {
//	
//	$jsFiles = getFiles('themes/' . THEME_FOLDER . '/' . JS_FOLDER);
//	for ($i = 0; $i < sizeof($jsFiles); $i++) {
//		echo('<link rel="stylesheet" href="/' . $cssFiles[$i] . '" type="text/css"/>');
//	}
//}

function getFiles($dir) {
		if (!is_dir($dir)) {
			return false;
		}
		
		$files = array();
		listdiraux($dir, $files);
		
		if (!$files) {
			return false;
		}
		sort($files, SORT_LOCALE_STRING);
		return($files);
	}

function displayNav($type, $title = Null) {
	global $posts;
	
	echo('<li><a class="nav-title" href="/posts/?t=' . $type . '">' . $title . '</a><ul>' . "\n");
		
	for ($i = (sizeof($posts)-1); 0 <= $i; $i--) {
		if($posts[$i]->getType() == $type) {
			echo('<li><a href="/posts/?p=' . $posts[$i]->getLink() . '">' . $posts[$i]->getTitle() . '</a></li>');
		}
	}
	
	echo('</ul></li>');
}

function displayCommentForm() {
	include(findRoot() . 'themes/' .  THEME_FOLDER . '/comment.php');
}

function displayFooter() {
	include(findRoot() . 'themes/' .  THEME_FOLDER . '/footer.php');
}

function displayFooterLinks() {
	global $FOOTER_LINKS;
	$index = 0;
	foreach ($FOOTER_LINKS as $name => $link) {
		echo('<a href="' . $link . '">' . $name . '</a>');
		if ($index != (sizeof($FOOTER_LINKS) - 1)) {
			echo(FOOTER_SEPERATOR);
		}
		$index++;
	}
	
	//Turn on Theme switcher
	if (THEME_SWITCHER) {

        global $themes;
        
        foreach ($themes as $key => $value) {
            echo(FOOTER_SEPERATOR . '<a href="/" onClick="createCookie(\'theme\', \'' . $key . '\', ' . THEME_TIME . ')">' . $value . '</a>');
        }
        
	}
}

?>