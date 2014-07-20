Title: Nine &#10093;&#10093; Pagination
Type: assignment
Date: 2012-03-12
Time Spent: 180

Since what Bill gave out was built for his sprockets application I had to built my own implementation of the pager class. This is what I came up with:

	<?php
	function displayPagation($numPosts, $currentPage = 0) {
		
		$numOfPages = $numPosts / ARTICLES_PER_PAGE;
		
		if ($numOfPages > 1) {
		
			echor('<div id="pagation"><ul>');
			
			//Previous
			if ($currentPage != 0) {
				echor('<li class="next-previous"><a href="' . addPGquery($currentPage - 1) . '">Previous</a></li>');
			}
			
			//Pages
			for ($i=0; $i < $numOfPages; $i++) {
				if ($currentPage == $i) {
					echor('<li><a id="current-page" href="' . addPGquery($i) . '">' . ($i + 1) . '</a></li>');
				} else {
					echor('<li><a href="' . addPGquery($i) . '">' . ($i + 1 ) . '</a></li>');
				}
			}
			
			//Next
			if (!($currentPage+1 >= floor($numOfPages))) {
				echor('<li class="next-previous"><a href="' . addPGquery($currentPage + 1) . '">Next</a></li>');
			}
			
			echor('</ul></div>');
			
		}
	}
	
	function addPGquery($toAdd) {
		$url = $_SERVER['REQUEST_URI'];
		$url = removeQuery($url, 'pg');
		$url = addQuery($url, 'pg', $toAdd);
		return($url);
	}
	
	function addQuery($url, $key, $value) {
	    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
	    $url = substr($url, 0, -1);
	    if (strpos($url, '?') === false) {
	        return ($url . '?' . $key . '=' . $value);
	    } else {
	        return ($url . '&' . $key . '=' . $value);
	    }
	}
	
	function removeQuery($url, $key) {
	    $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
	    $url = substr($url, 0, -1);
	    return ($url);
	}
	?>

I define `ARTICLES_PER_PAGE` as a constant in my `config.php` file. I added a class to the current page and keep any existing query in the url. The only problem is that if the `pg` variable in the query sting is the only one in the query it won't replace the first one; it will just ammend another one after it. If it's not the only query and I have an existing one that's not `pg` it will then add it and replace that same one when the page changes as expected. I believe that this has something to do with the regexp in the `removeQueary` function. Credit goes to [AddedBytes](http://www.addedbytes.com/lab/php-querystring-functions/) for the `addQuery` and the `removeQuery` functions.
