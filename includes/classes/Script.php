<?php

abstract class Script {

	//VARIABLES
	public static
	$charset,
	$defer,
	$id,
	$inline,
	$language,
	$src,
	$type;
	
	protected static $openingTag = '<script';
	protected static $closingTag = '</script>';
	
	//METHODS
	
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

?>
