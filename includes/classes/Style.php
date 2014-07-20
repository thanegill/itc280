<?php

class Style {

	//VARIABLES
	public
	$dir,
	$id,
	$inline,
	$lang,
	$media,
	$title,
	$type;
	
	private $openingTag = '<style';
	private $closingTag = '</style>';
	
	//CONSTRUCTOR
	public function __construct($inline, $media='screen', $type='text/css') {
		$this->inline = $inline;
		$this->type = $type;
		$this->media = $media;
	}
	
	public function printStyle() {
		echo($this->openingTag . ' ' . $this->getAttributes() . '>' . $this->inline . $this->closingTag . "\n");
	}
	
	protected function getAttributes() {
		$classVars = get_object_vars($this);
		$return = '';
		foreach ($classVars as $key => $value) {
			if(!empty($value) && $key != 'openingTag' && $key != 'closingTag' && $key != 'inline') {
				$return .= (str_replace('_', '-', $key) . '="' . $value . '" ');
			}
		}
		
		return($return);
	}

}

//$myStyle = new Style('
//body {
//	color: red;	
//}
//');
//
//$myStyle->printStyle();

?>