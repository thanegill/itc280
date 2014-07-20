<?php

abstract class Link {

	//VARIABLES
	public static
	$charset,
	$class,
	$dir,
	$href,
	$hreflang,
	$id,
	$lang,
	$media,
	$rel,
	$rev,
	$style,
	$target,
	$title,
	$type;
	
	protected static $openingTag = '<link';
	protected static $closingTag = '>';
	
	//METHODS
	protected function getAttributes() {
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

?>
