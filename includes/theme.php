<?php
function echor($str) {
	echo($str . "\n");
}
function echobr($str) {
	echo($str . '<br />');
}

function getRelPath($path) {
	return(substr($path, strlen(findRoot())-1, strlen($path)));
}

function getArticles($dir) {
	if (!is_dir($dir)) {
		return false;
	}

	$files = array();
	listdiraux($dir, $files);

	if (!$files) {
		return false;
	}
	sort($files, SORT_LOCALE_STRING);
	$toReturn = array();
	foreach ($files as $key => $value) {
		$toReturn[$key] = new Post($value);
	}

	return(array_reverse($toReturn));
}

function displayHead($pageTitle = '') {
	global $articles;
	define('PAGE_TITLE', $pageTitle);
	include(findRoot() . 'themes/' .  THEME_FOLDER . '/head.php');
}

function displayFooter() {
	include(findRoot() . 'themes/' .  THEME_FOLDER . '/footer.php');
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
	global $articles;

	echor('<li><a class="nav-title" href="/?t=' . $type . '">' . $title . '</a><ul>');

	for ($i = 0; $i < sizeof($articles); $i++) {
		if($articles[$i]->getType() == $type) {
			echor('<li><a href="/?a=' . $articles[$i]->getLink() . '">' . $articles[$i]->getTitle() . '</a></li>');
		}
	}

	echor('</ul></li>');
}

function displayPost($postLink) {
	global $articles;
	$error = true;

	for ($i = 0; $i < sizeof($articles); $i++) {
		if ($articles[$i]->getLink() == $postLink) {
			displayHead($articles[$i]->GetFullTitle());
			$articles[$i]->displayArticle();
			$articles[$i]->displayComments();
			displayCommentForm();
			$error = false; //trips error to false if article found
		}
	}

	//article not found
	if ($error) {
		display404Error();
	}
}

function displayType($type = 'all') {
	global $articles, $ARTICLE_TYPES;

	if (!empty($_GET['pg']) && isset($_GET['pg'])) {
		$pg = $_GET['pg'];
	} else {
		$pg = 0;
	}

	if (in_array($type, $ARTICLE_TYPES)) {

		displayHead(array_search($type, $ARTICLE_TYPES));

		$articlesForPage = array();

		for ($j = 0; $j < sizeof($articles); $j++) {
			if ($articles[$j]->getType() == $type) {
				array_push($articlesForPage, $articles[$j]);
			}
		}

		//Get number of posts in type
		$numOfPages = sizeof($articlesForPage) / ARTICLES_PER_PAGE;

		$start = $pg * ARTICLES_PER_PAGE;
		$end = $start + ARTICLES_PER_PAGE;

		//If end goes over length of $articlesForPage
		if ($end > sizeof($articlesForPage)) {
			$end = sizeof($articlesForPage);
		}

		//display posts
		for ($i = $start; $i < $end; $i++) {
			$articlesForPage[$i]->displayArticle();
		}

		displayPagation(sizeof($articlesForPage), $pg);

	} elseif ($type == 'all') {
		//display all types
		displayHead('Blog');

		//Get number of posts in type
		$numOfPages = sizeof($articles) / ARTICLES_PER_PAGE;

		$start = ($pg * ARTICLES_PER_PAGE);
		$end = $start + ARTICLES_PER_PAGE;

		//If end goes over length of $articlesForPage
		if ($end > sizeof($articles)) {
			$end = sizeof($articles);
		}

		//display posts
		for ($i = $start; $i < $end; $i++) {
			$articles[$i]->displayArticle();
		}

		displayPagation(sizeof($articles), $pg);

	} else {
		display404Error();
	}
}

function displayPagation($numPosts, $currentPage = 0) {

	$numOfPages = $numPosts / ARTICLES_PER_PAGE;

	if ($numOfPages > 1) {

		echor('<div id="pagation"><ul>');

		//Previous
		if ($currentPage != 0) {
			echor('<li class="next-previous"><a href="' . addPGquery($currentPage - 1) . '">Previous</a></li>');
		}

		//Pages
		for ($i=0; $i < $numOfPages; $i++) {
			if ($currentPage == $i) {
				echor('<li><a id="current-page" href="' . addPGquery($i) . '">' . ($i + 1) . '</a></li>');
			} else {
				echor('<li><a href="' . addPGquery($i) . '">' . ($i + 1 ) . '</a></li>');
			}
		}

		//Next
		if (!($currentPage >= floor($numOfPages))) {
			echor('<li class="next-previous"><a href="' . addPGquery($currentPage + 1) . '">Next</a></li>');
		}

		echor('</ul></div>');

	}
}

function addPGquery($toAdd) {
	$url = $_SERVER['REQUEST_URI'];
	$url = removeQuery($url, 'pg');
	$url = addQuery($url, 'pg', $toAdd);
	return($url);
}

function addQuery($url, $key, $value) {
    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
    $url = substr($url, 0, -1);
    if (strpos($url, '?') === false) {
        return ($url . '?' . $key . '=' . $value);
    } else {
        return ($url . '&' . $key . '=' . $value);
    }
}

function removeQuery($url, $key) {
	//TODO:
    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
    $url = substr($url, 0, -1);
    return ($url);
}

function displayCommentForm() {
	include(findRoot() . 'themes/' .  THEME_FOLDER . '/comment.php');
}

function displayFooterLinks() {
	global $FOOTER_LINKS;
	$index = 0;
	foreach ($FOOTER_LINKS as $name => $link) {
		echo('<a href="' . $link . '">' . $name . '</a>');
		if ($index != (sizeof($FOOTER_LINKS) - 1)) {
			echo(FOOTER_SEPERATOR . "\n");
		}
		$index++;
	}

	//Theme switcher
	if (THEME_SWITCHER) {

        global $themes;

        foreach ($themes as $key => $value) {
            echo(FOOTER_SEPERATOR . "\n" . '<a href="/" onClick="createCookie(\'theme\', \'' . $key . '\', ' . THEME_TIME . ')">' . $value . '</a>');
        }

	}
}

function display404Error() {
	displayHead('Error 404 - No Post');
	echo('<article>
		<h1>404 Article Doesnt Exsist</h1>
		<h3><a href="/">Back Home</a></h3>
		</article>');
}

function listdiraux($dir, &$files) {
	$handle = opendir($dir);

	while (($file = readdir($handle)) !== false) {

		if ($file ==  '.' || $file == '..') {
			continue;
		}

		$filepath = $dir == '.' ? $file : $dir . '/' . $file;

		if (is_link($filepath)) {
			continue;
		}

		if (is_file($filepath)) {
			$files[] = $filepath;
		} else if (is_dir($filepath)) {
			listdiraux($filepath, $files);
		}
	}
	closedir($handle);
}

//Display Time; 8:23
function displayTimeSpent($time) {
	$hours = floor($time / 60);
	$minutes = ($time % 60);

	if ($minutes == 0 ) {
		$minutes = "00";
	}
	return($hours . ":" . $minutes);
}

function linkText($text) {
	$text = preg_replace('/\%/',' percentage',$text);
	$text = preg_replace('/\@/',' at ',$text);
	$text = preg_replace('/\&/',' and ',$text);
	$text = preg_replace('/\s[\s]+/','-',$text); //Strip off multiple spaces
	$text = preg_replace('/[\s\W]+/','-',$text); //Strip off spaces and non-alpha-numeric
	$text = preg_replace('/^[\-]+/','',$text); //Strip off the starting hyphens
	$text = preg_replace('/and-10093-/','',$text); //Strip off the ending hyphens
	$text = preg_replace('/[\-]+$/','',$text); //Strip off the ending hyphens
	$text = strtolower($text);

	return($text);
}

function int2words($x) {
	$nwords = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty", 30 => "thirty", 40 => "forty", 50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty", 90 => "ninety" );
	if (!is_numeric($x)) {
		$w = '#';
	} else if (fmod($x, 1) != 0) {
		$w = '#';
	} else {
		if ($x < 0) {
			$w = 'minus ';
			$x = -$x;
		} else {
			$w = '';
		}
		if ($x < 21) {
			$w .= $nwords[$x];
		} else if ($x < 100) {
			$w .= $nwords[10 * floor($x/10)];
			$r = fmod($x, 10);
			if ($r > 0) {
				$w .= '-'. $nwords[$r];
			}
		} else if($x < 1000) {
			$w .= $nwords[floor($x/100)] .' hundred';
			$r = fmod($x, 100);
			if ($r > 0) {
				$w .= ', '. int2words($r);
			}
		} else if ($x < 100000) {
			$w .= int2words(floor($x/1000)) .' thousand';
			$r = fmod($x, 1000);
			if ($r > 0) {
				$w .= ' ';
				if ($r < 100) {
					$w .= ', ';
				}
				$w .= int2words($r);
			}
		} else {
			$w .= int2words(floor($x/100000)) .' hundred-thousand';
			$r = fmod($x, 100000);
			if ($r > 0) {
				$w .= ' ';
				if ($r < 100) {
					$word .= ', ';
				}
				$w .= int2words($r);
			}
		}
	}
	return $w;
}

?>
