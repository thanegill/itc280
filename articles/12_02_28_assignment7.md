Title: Seven &#10093;&#10093; Theming
Type: assignment
Date: 2012-02-27
Time Spent: 120


Instead of using Bills Sprockets app I used my own file based "CMS" that this site is running off of and created multiple themes for it. I placed **links in the footer** that set a cookie via javascript. Those cookies are read by PHP on the page load. A default theme is loaded if the theme cookie isn't set yet.

Each theme has this structure:

	theme-folder
		+- css : CSS files go here
		|   +- style.css
		|
		+- fonts : Font files go here
		|   +- font.eot
		|   +- font.svg
		|   +- font.ttf
		|   +- font.woff
		|
		+- js : JS files go here
		|   +- jquery.js
		|   +- validate.js
		|   +- util.js
		|
		+- images
		|   +- background.png : CSS images links go here
		|
		+- comment.php : Holds the comment form
		|
		+- footer.php : HTML bottom
		|
		+- head.php : HTML top

The only files that are absolutely needed are the header.php and footer.php.

Any files in the js or css fodler are automatically link into the head. No manual linking!

Any CSS image or font links should be linked relative to the css file like this:

	body {
		width: 760px;
		margin: 0 auto;
		background-image: url("../images/linen.png");
	}
	
	@font-face {
	    font-family: 'LeagueGothic';
	    src: url('../fonts/leaguegothic.eot');
	    src: url('../fonts/leaguegothic.eot?#iefix') format('embedded-opentype'),
	         url('../fonts/leaguegothic.woff') format('woff'),
	         url('../fonts/leaguegothic.ttf') format('truetype'),
	         url('../fonts/leaguegothic.svg') format('svg');
	    font-weight: normal;
	    font-style: normal;
	}

They are linked like this so that you don't have to find the absolute path to the image or font.

I've tried to not hard code any file paths. In my config folder I have the name of the javascript and css folder, that can be easily changed as well as a way to turn on or of the theme switching links in the footer.

This is my config.php file that hold the theme setting as well as other site wide settings:
	
	<?php
	//Import Credentials file for MySQL
	include('credentials.php');
	
	//Doctype for site
	define('DOCTYPE', '<!DOCTYPE html>');
	
	//Default theme to load
	define('DEFAULT_THEME', 'linen');
	
	//Turn on or off Theme link in footer (TRUE/FALSE)
	define('THEME_SWITCHER', TRUE);
	
	//Themes ('theme-folder' => 'Name of Theme',)
	$themes = array(
	'side-nav'			=> 'Side Nav',
	'noisy-grey'		=> 'Top Nav',
	'linen'				=> 'Linen',
	'natural-essence'	=> 'Natural Essence'
	);
	
	//Folder where articles live
	define('ARTICLE_FOLDER', 'posts_content');
	
	//Theme Cookie Time out in days
	define('THEME_TIME', '30');
	
	//CSS foler in theme to load (no slashes)
	define('CSS_FOLDER', 'css');
	
	//JS foler in theme to load (no slashes)
	define('JS_FOLDER', 'js');
	
	//Turn on or off html5 shiv/shim (TRUE/FALSE)
	define('DISPLAY_SHIM', TRUE);
	
	//Turn on or off IE redirect
	define('IE_BUMP', TRUE);
	
	//Location to bump IE users to
	define('IE_BUMP_LOCATION', '/ie_sucks.html');
	
	//Header across all pages at top
	define('SITE_NAME', 'Thane Gill &#10093; ITC 280');
	
	//What the Title tag end with. (Inclue a space at the begging)
	define('TITLE_TAG_END', ' | ITC 280 | Thane Gill');
	
	//Comment instuctions, Just above the comment form.
	define('COMMENT_INSTUSTIONS', '<em>Any comments or questions that you have please post them below. I will receive an email of it and post it to the site after moderation.</em>');
	
	//The Seperator for footer Links (incude the spaces here if wanted)
	define('FOOTER_SEPERATOR', ' | ');
	
	//links in the footer('Name of link' => 'http://ulr-link.com/',)
	$FOOTER_LINKS = array(
	'Contact' 				=> '/contact/',
	'Follow Me on Twitter' 	=> 'http://twitter.com/#!/thanegill',
	'thanegill.com' 		=> 'http://thanegill.com/',
	'&copy; ' . date('Y')	=> '#',
	'Validate HTML' 		=> 'http://validator.w3.org/check?uri=referer',
	'Validate CSS' 			=> 'http://jigsaw.w3.org/css-validator/check/referer?profile=css3'
	);
	
	?>
