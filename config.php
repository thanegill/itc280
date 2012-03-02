<?php
//Import Credentials file for MySQL
include('credentials.php');

//Doctype for site
define('DOCTYPE', '<!DOCTYPE html>');

//Article types ('Artlce Type' => 'articletype',)
$ARTICLE_TYPES = array(
'Assignment Posts' 			=> 'assignment',
'Extra Credit Posts' 		=> 'extracredit',
'Resource Posts' 			=> 'resource'
);

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

//links in the footer ('Name of link' => 'http://ulr-link.com/',)
$FOOTER_LINKS = array(
'Contact' 					=> '/contact/',
'Follow Me on Twitter' 		=> 'http://twitter.com/#!/thanegill',
'thanegill.com' 			=> 'http://thanegill.com/',
'&copy; ' . date('Y')		=> '#',
'Validate HTML' 			=> 'http://validator.w3.org/check?uri=referer',
'Validate CSS' 				=> 'http://jigsaw.w3.org/css-validator/check/referer?profile=css3'
);

?>