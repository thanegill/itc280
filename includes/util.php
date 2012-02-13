<?php
function listdiraux($dir, &$files) {
	$handle = opendir($dir);
	
	while (($file = readdir($handle)) !== false) {
	
		if ($file ==  '.' || $file == '..') {
			continue;
		}
		
		$filepath = $dir == '.' ? $file : $dir . '/' . $file;
			
		if (is_link($filepath)) {
			continue;
		}
		
		if (is_file($filepath)) {
			$files[] = $filepath;
		} else if (is_dir($filepath)) {
			listdiraux($filepath, $files);
		}
	}
	closedir($handle);
}

function getPosts($dir) {
	if (!is_dir($dir)) {
		return false;
	}
	
	$files = array();
	listdiraux($dir, $files);
	
	if (!$files) {
		return false;
	}
	sort($files, SORT_LOCALE_STRING);
	$toReturn = array();
	foreach ($files as $key => $value) {
		$toReturn[$key] = new Post($value);
	}
	
	return($toReturn);
}

//Display Time; 8:23
function displayTimeSpent($time) {
	$hours = floor($time / 60);
	$minutes = ($time % 60);
	
	if ($minutes == 0 ) {
		$minutes = "00";
	}
	return($hours . ":" . $minutes);
}

function linkText($text) {	
	$text = preg_replace('/\%/',' percentage',$text);
	$text = preg_replace('/\@/',' at ',$text);
	$text = preg_replace('/\&/',' and ',$text);
	$text = preg_replace('/\s[\s]+/','-',$text); //Strip off multiple spaces 
	$text = preg_replace('/[\s\W]+/','-',$text); //Strip off spaces and non-alpha-numeric 
	$text = preg_replace('/^[\-]+/','',$text); //Strip off the starting hyphens 
	$text = preg_replace('/and-10093-/','',$text); //Strip off the ending hyphens
	$text = preg_replace('/[\-]+$/','',$text); //Strip off the ending hyphens 
	$text = strtolower($text);
	
	return($text);
}

function int2words($x) {
	$nwords = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty", 30 => "thirty", 40 => "forty", 50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty", 90 => "ninety" );
	if (!is_numeric($x)) {
		$w = '#';
	} else if (fmod($x, 1) != 0) {
		$w = '#';
	} else {
		if ($x < 0) {
			$w = 'minus ';
			$x = -$x;
		} else {
			$w = '';
		}
		if ($x < 21) {
			$w .= $nwords[$x];
		} else if ($x < 100) {
			$w .= $nwords[10 * floor($x/10)];
			$r = fmod($x, 10);
			if ($r > 0) {
				$w .= '-'. $nwords[$r];
			}
		} else if($x < 1000) {
			$w .= $nwords[floor($x/100)] .' hundred';
			$r = fmod($x, 100);
			if ($r > 0) {
				$w .= ', '. int2words($r);
			}
		} else if ($x < 100000) {
			$w .= int2words(floor($x/1000)) .' thousand';
			$r = fmod($x, 1000);
			if ($r > 0) {
				$w .= ' ';
				if ($r < 100) {
					$w .= ', ';
				}
				$w .= int2words($r);
			}
		} else {
			$w .= int2words(floor($x/100000)) .' hundred-thousand';
			$r = fmod($x, 100000);
			if ($r > 0) {
				$w .= ' ';
				if ($r < 100) {
					$word .= ', ';
				}
				$w .= int2words($r);
			}
		}
	}
return $w;
}

?>