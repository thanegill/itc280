<?php

class Meta {

	//VARIABLES
	public static
	$content,
	$http_equiv,
	$lang,
	$name,
	$scheme;
	
	private $openingTag = '<meta';
	private $closingTag = '>';
	
	//CONSTRUCTOR
	public function __construct($name='', $content='', $http_equiv='', $lang='', $scheme='') {
		$this->name = $name;
		$this->content = $content;
		$this->http_equiv = $http_equiv;
		$this->lang = $lang;
		$this->scheme = $scheme;
	}
	
	//METHODS
	public function printMeta() {
		echo($this->openingTag . ' ' . $this->getAttributes() . $this->closingTag . "\n");
	}
	
	public function getAttributes() {
		$classVars = get_object_vars($this);
		$return = '';
		foreach ($classVars as $key => $value) {
			if(!empty($value) && $key != 'openingTag' && $key != 'closingTag') {
				$return .= (str_replace('_', '-', $key) . '="' . $value . '" ');
			}
		}
		
		return($return);
	}
}

//$myMeta = new Meta('Author', 'Thane Gill', 'hey');

//var_dump($myMeta);

//$myMeta->printMeta();

//echo($myMeta->getAttributes());

?>
