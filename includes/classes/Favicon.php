<?php

include_once('Link.php');

class Favicon extends Link {
		
	//CONSTRUCTOR
	public function __construct($href) {
		$this->href = $href;
		$this->rel = 'icon';
	}
	
	//METHODS
	public function printFavicon() {
		echo(parent::$openingTag . ' ' . parent::getAttributes() . parent::$closingTag . "\n");
		$this->rel = 'shortcut icon';
		echo(parent::$openingTag . ' ' . parent::getAttributes() . parent::$closingTag . "\n");
	}
}

?>
