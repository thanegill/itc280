<?php
//Import Credentials file for MySQL
include('credentials.php');

//Doctype for site
define('DOCTYPE', '<!DOCTYPE html>');

//Number of articles per pager in the pagation
define('ARTICLES_PER_PAGE', 4);

//Article types ('Artlce Name' => 'articletype',)
$ARTICLE_TYPES = array(
'Assignment Posts'   => 'assignment',
'Extra Credit Posts' => 'extracredit',
'Resource Posts'     => 'resource',
'Article'            => 'article'
);

//Default theme to load
define('DEFAULT_THEME', 'noisy-grey');

//Turn on or off Theme link in footer (TRUE/FALSE)
define('THEME_SWITCHER', TRUE);

//Themes ('theme-folder' => 'Name of Theme',)
$themes = array(
'side-nav'        => 'Side Nav',
'noisy-grey'      => 'Top Nav',
'linen'           => 'Linen',
'natural-essence' => 'Natural Essence'
);

//Folder where articles live
define('ARTICLE_FOLDER', 'articles');

//Theme Cookie Timeout (days)
define('THEME_TIME', '30');

//CSS foler in theme to load (no slashes)
define('CSS_FOLDER', 'css');

//JS foler in theme to load (no slashes)
define('JS_FOLDER', 'js');

//Turn on or off html5 shiv/shim (TRUE/FALSE)
define('DISPLAY_SHIM', TRUE);

//Turn on or off IE redirect (TRUE/FALSE)
define('IE_BUMP', FALSE);

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
'Contact'              => '/contact/',
'Follow Me on Twitter' => 'http://twitter.com/#!/thanegill',
'thanegill.com'        => 'http://thanegill.com/',
'&copy; ' . date('Y')  => '#',
'Validate HTML'        => 'http://validator.w3.org/check?uri=referer',
'Validate CSS'         => 'http://jigsaw.w3.org/css-validator/check/referer?profile=css3'
);

define('FAVICON', 'icon.png');

define('FIGLET', '<!--#####################################################
##      ______                           __   ______   ##
##     / ____/___  ____  _ ____  ___    / / /  __   |  ##
##    / __/ / __ \/ __ `/ / __ \/ _ \  / / |  /_/  /   ##
##   / /___/ / / / /_/ / / / / /  __/ / / /  __   /    ##
##  /_____/_/ /_/\__, /_/_/ /_/\___/ / / /  /_/  |     ##
##           /_______/ D E S I G N  /_/  \______/      ##
##                                                     ##
##                 engine18design.com                  ##
##                   copyright 2012                    ##
######################################################-->');

$GLOBAL_META = array(
'Author'  => 'Thane Gill',
'email'   => 'me@thanegill.com',
'website' => 'thanegill.com'
);

?>
