<?php

include('classes/CSS.php');
include('classes/JS.php');
include('classes/Meta.php');
include('classes/Style.php');
include('classes/Favicon.php');

class Head {

	//VARIABLES
	public
	$doctype,
	$figlet,
	$title,
	$meta,
	$favicon,
	$CSS,
	$JS;
	
	private $openingTag = "<html>\n<head>";
	private $closingTag = '</head>';

	//CONSTRUCTOR
	public function __construct($doctype, $title, $favicon, $meta, $CSS, $JS, $figlet='') {
		$this->doctype = $doctype;
		$this->figlet = $figlet;
		$this->title = $title;
		$this->favicon = $favicon;
		$this->meta = $meta;
		$this->CSS = $CSS;
		$this->JS = $JS;

	}

	//METHODS
	public function printHead() {
		$begining = array(
			$this->doctype,
			$this->figlet,
			$this->openingTag,
			'<title>' . $this->title . '</title>'
		);
		
		echo(implode("\n", $begining) . "\n\n");
		
		$this->printMeta();
		$this->favicon->printFavicon();
		$this->printCSS();
		$this->printJS();
		
		echo($this->closingTag . "\n");
	}
	
	//PRIVATE METHODS
	private	function printMeta() {
		foreach ($this->meta as $i) {
			$i->printMeta();
		}
		echo("\n");
	}
	
	private	function printCSS() {
		foreach ($this->CSS as $i) {
			$i->printCSS();
		}
		echo("\n");
	}
	
	private	function printJS() {
		foreach ($this->JS as $i) {
			$i->printJS();
		}
		echo("\n");
	}
}

//$meta = array(
//	new Meta('Author', 'Thane Gill'),
//	new Meta('viewport', 'width=1060')
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
//#                  engine18design.com                 ##
//#                    copyright 2012                   ##
//#####################################################-->';
//
//$myHead = new Head('<!DOCTYPE html>', 'My Cool Title', $favicon, $meta, $CSS, $JS, $figlet);
//
//var_dump($myHead);
//
//$myHead->printHead();
?>
