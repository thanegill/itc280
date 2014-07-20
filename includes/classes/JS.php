<?php

include_once('Script.php');

class JS extends Script {

	//VARIABLES

	//CONSTRUCTOR
	public function __construct($src = '', $inline = '', $type='text/javascript') {
		$this->src = $src;
		$this->inline = $inline;
		$this->type = $type;
	}
	
	//METHODS
	public function printJS() {
		echo(parent::$openingTag . ' ' . parent::getAttributes() . '>' . $this->inline . parent::$closingTag . "\n");
	}
}

//$mylink = new JS('util.js');
//
//$mylink->printJS();
//
//$myInLineJS = new JS('','alert("hey");');
//$myInLineJS->printJS();

?>