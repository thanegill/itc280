<?php
function getRelPath($path) {
	return(substr($path, strlen(findRoot())-1, strlen($path)));
}


function displayHead($title = '') {
	global $posts;
	$pageTitle = $title;
	include(findRoot() . 'themes/' .  THEME_FOLDER . '/head.php');
}

function displayCSS() {
	$cssFiles = getFiles(findRoot() . 'themes/' . THEME_FOLDER . '/' . CSS_FOLDER);
	for ($i = 0; $i < sizeof($cssFiles); $i++) {
		$cssFiles[$i] = substr($cssFiles[$i], strlen(findRoot())-1, strlen($cssFiles[$i]));
		echor('	<link rel="stylesheet" type="text/css" href="' . $cssFiles[$i] . '" />');
	}
}

function displayJS() {
	$jsFiles = getFiles(findRoot() . 'themes/' . THEME_FOLDER . '/' . JS_FOLDER);
	for ($i = 0; $i < sizeof($jsFiles); $i++) {
		echor('	<script type="text/javascript" src="' . getRelPath($jsFiles[$i]) . '" ></script>');
	}
}

function displayShim() {
	if (DISPLAY_SHIM) {
		echor('	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->');
	}
}

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
	
	echor('<li><a class="nav-title" href="/posts/?t=' . $type . '">' . $title . '</a><ul>');
		
	for ($i = (sizeof($posts)-1); 0 <= $i; $i--) {
		if($posts[$i]->getType() == $type) {
			echor('<li><a href="/posts/?a=' . $posts[$i]->getLink() . '">' . $posts[$i]->getTitle() . '</a></li>');
		}
	}
	
	echor('</ul></li>');
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
	
	//Theme switcher
	if (THEME_SWITCHER) {

        global $themes;
        
        foreach ($themes as $key => $value) {
            echo(FOOTER_SEPERATOR . '<a href="/" onClick="createCookie(\'theme\', \'' . $key . '\', ' . THEME_TIME . ')">' . $value . '</a>');
        }
        
	}
}

?>