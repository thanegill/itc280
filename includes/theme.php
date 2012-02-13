<?php
function displayHead($title) {
	global $posts;
	$pageTitle=$title;
	include(findRoot() . 'themes/' .  THEME_FOLDER . '/head.php');
}

function displayNav($type, $title = Null) {
	global $posts;
	
	echo('<li><a href="/posts/?t=' . $type . '">' . $title . '</a><ul>');
		
	for ($i=(sizeof($posts)-1); 0 <= $i; $i--) {
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
		if ((sizeof($FOOTER_LINKS) - 1) != $index) {
			echo(FOOTER_SEPERATOR);
		}
		$index++;
	}
}

?>