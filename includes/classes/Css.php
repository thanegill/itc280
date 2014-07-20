<?php

include_once('Link.php');

class CSS extends Link {

	//VARIABLES

	//CONSTRUCTOR
	public function __construct($href, $media='screen', $rel='stylesheet', $type='text/css') {
		$this->rel = $rel;
		$this->href = $href;
		$this->type = $type;
		$this->media = $media;
	}
	
	//METHODS
	public function printCSS() {
		echo(parent::$openingTag . ' ' . parent::getAttributes() . parent::$closingTag . "\n");
	}


}

//$mylink = new CSS('sytle.css');
//
//$mylink->printCSS();

?>
