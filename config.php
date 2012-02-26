<?php
//mySQL Config
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', 'root');
define('MYSQL_DATABASE', 'itc280');

//Default theme to load
define('DEFAULT_THEME', 'noisy-grey');

//Turn on or off Theme link in footer
define('THEME_SWITCHER', TRUE);

//Themes ('theme-folder' => 'Name of Theme',)
$themes = array(
'side-nav'		=> 'Side Nave Theme',
'noisy-grey'	=> 'Top Nav Theme',
'dark'			=> 'Dark Theme'
);

//Theme Cookie Time out in days
define('THEME_TIME', '30');

//CSS foler in theme to load (no slashes)
define('CSS_FOLDER', 'css');

//todo:
//JS foler in theme to load (no slashes)
//define('JS_FOLDER', 'js');

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
'Contact' 					=> '/contact/',
'Follow Me on Twitter' 		=> 'http://twitter.com/#!/thanegill',
'thanegill.com' 			=> 'http://thanegill.com/',
'&copy; ' . date('Y')		=> '#',
'Validate HTML' 			=> 'http://validator.w3.org/check?uri=referer',
'Validate CSS' 				=> 'http://jigsaw.w3.org/css-validator/check/referer?profile=css3'
);

?>