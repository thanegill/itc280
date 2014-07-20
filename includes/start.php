<?php

include('../run.php');

include('classes/Page.php');
include('dbconnector.php');

//$meta = array(
//	new Meta('Author', 'Thane Gill'),
//	new Meta('viewport', 'width=1024')
//);
//
//$favicon = new Favicon('icon.png');
//
//$CSS = array(
//	new CSS('style.css'),
//	new CSS('css.css'),
//	new CSS('master.css'),
//	new CSS('style2.css')
//);
//
//$JS = array(
//	new JS('util.js'),
//	new JS('', 'alert("hello");'),
//	new JS('validate.js')
//);
//
//$figlet = '<!--#####################################################
//#  	______                           __   ______   ##
//#     / ____/___  ____  _ ____  ___    / / /  __   |  ##
//#    / __/ / __ \/ __ `/ / __ \/ _ \  / / |  /_/  /   ##
//#   / /___/ / / / /_/ / / / / /  __/ / / /  __   /    ##
//#  /_____/_/ /_/\__, /_/_/ /_/\___/ / / /  /_/  |     ##
//#           /_______/ D E S I G N  /_/  \______/      ##
//#                                                     ##
//#                 engine18design.com                  ##
//#                   copyright 2012                    ##
//#####################################################-->';
//
//$myHead = new Head('<!DOCTYPE html>', 'My Cool Title', $favicon, $meta, $CSS, $JS, $figlet);
//
//var_dump($myHead);
//
//$myHead->printHead();
//
//$header = '';
//
//$footer='	</div><!--#content-->
//		<footer class="wrap">
//			<small id="footer_links">
//				<?php displayFooterLinks();
//			 </small><!--footer_links-->
//		</footer>
//	</div><!--#body_inner-->';
//
//
//$myPage = new Page($myHead, $header, 'the content', $footer);
//
//$myPage->printPage();

//$meta = array(
//'Author2'	=> 'Thane Gill2',
//'email2'	=> 'me@thanegill.com2',
//'website2'	=> 'thanegill.com2'
//);
//
//
//
//pageCreator('a title', $meta, 'the nav/header', 'the content', 'my stupid footer');
//
//
//function pageCreator($title, $meta, $header, $content, $footer) {
//
//	$head = new head(DOCTYPE, $title . TITLE_TAG_END, FAVICON, getMeta($meta), getCSS(), getJS(), FIGLET);
//	
//	$page = new Page($head, $header, $content, $footer);
//	
//	var_dump($page);
//	
//	$page->printPage();
//}
//
//
//function getCSS() {
//	$cssFiles = getFiles(THEME_FOLDER . '/' . CSS_FOLDER);
//	
//	for ($i = 0; $i < sizeof($cssFiles); $i++) {
//		$cssFiles[$i] = new CSS(getVirturalPath($cssFiles[$i]));
//	}
//	
//	return($cssFiles);
//}
//
//function getJS() {
//	$JSFiles = getFiles(THEME_FOLDER . '/' . JS_FOLDER);
//	
//	for ($i = 0; $i < sizeof($JSFiles); $i++) {
//		$JSFiles[$i] = new JS(getVirturalPath($JSFiles[$i]));
//	}
//	
//	return($JSFiles);
//}
//
//
//function getMeta($extraMeta = array()) {
//	global $GLOBAL_META;
//	
//	$metaRead = array_merge($extraMeta, $GLOBAL_META);
//	
//	var_dump($metaRead);
//	
//	$metaReturn = array();
//	
//	$i = 0;
//	foreach ($metaRead as $key => $value) {
//		$metaReturn[$i] = new Meta($metaRead[$key], $metaRead[$value]);
//		$i++;
//	}
//	
//	var_dump($metaReturn);
//	
//	return($metaReturn);
//}






?>