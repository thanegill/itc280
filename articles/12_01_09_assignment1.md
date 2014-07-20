Title: One &#10093;&#10093; This Site
Type: assignment
Date: 2012-01-10
Time Spent: 480

***This post is old. I have rewritten the whole site from the ground up. See the [Post Class](/?a=extracredit-zero-post-class) article for an update.***

I wanted to challenge myself with this first project. I know enough about programming and PHP to do enough to get started. So I created a simple blog that has a database as an array thats included in every page. I learned a lot by doing this. The one thing that I really like is the key value pair dictionary. As an example this is the very crude and simple database that I have:


	$post_names = array("Three" => "assignment3", "Two" => "assignment2", "One" => "assignment1");

I have definitely spent too much time on this, but I'm having fun learning and putting it together.


##How to add a post

To add a new article to the site add the name to the array above and put a new file like the one below in a special folder:

	<?php
	    $articleTitle = "Article Name";
	    $articleContent = "<p>Article goes here.</p>";
	    $articleTimeSpent = 60; //In minutes
	    $articleDate = date("l, F jS Y", strtotime("2012-01-08"));
	    $articleComments = array("NameOfCommenter" => "<p>Their Comment.</p>");
	?>

Once I do that these functions parse and print that into html:

	//Display Articles
	function displayArticle($toInclude, $currentFolder) {
		
		//Is link up one directory?
		if ($currentFolder) {
			include("markdown.php");
			include("posts_content/" . $toInclude . ".php");
		} else {
			include("../markdown.php");
			include("../posts_content/" . $toInclude . ".php");
		}
		
		//Should I display $articleTimeSpent?
		if (!isset($articleTimeSpent)  || $articleTimeSpent == null || $articleTimeSpent == "") {
			$timeSpentWord = "";
		} else {
			$timeSpentWord = " - Time Spent: " . displayTime($articleTimeSpent);
		}
		
		//Pluralization of "comments"
		if (sizeof($articleComments) == 1) {
			$commentWord = "Comment";
		} else {
			$commentWord = "Comments";
		}
		
		echo("\n<article>
		<h1><a href=\"/posts/?post=" . $toInclude ."\">" . $articleTitle . "</a></h1>
		" . Markdown($articleContent) . "
		<span class=\"date-comments\">" . $articleDate . $timeSpentWord . " - <a href=\"/posts/?post=" . $toInclude . "#comments\">" . sizeof($articleComments) . " " . $commentWord . "</a></span>
		</article>");
	}
	
	//Display Comments
	function displayComments($toInclude) {
		include("../posts_content/" .$toInclude . ".php");
		
		if (sizeof($articleComments) > 0) {
			echo("<div id=\"comments\"><h3>Comments</h3>");
			
			foreach ($articleComments as $name => $comment) {
				echo("<div class=\"article-comment\"><h5>" . $name . ":</h5>". $comment . "</div>");
			}
			
			echo("</div>");
		}
	}
	
	function displayTime($time) {
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		
		if ($minutes == 0 ) {
			$minutes = "00";
		}
		
		return($hours . ":" . $minutes);
	}

##Displaying dates

I also learned how to display the dates. Which is a lot more complicated than you'd think. This is how you'd print the current date: `date("l\, F jS Y");`. This would print out "Tuesday, January 10th 2012". To store a date and print that you need to use `strtotime();` in conjunction with `date();`. This is what you get: `date("l\, F jS Y", strtotime("2012-01-10"));` Which again would print out: "Tuesday, January 10th 2012".

I ran into some problems when using the same method to save the time that I took on each assignment then calculate the total in the time tracker. I figured out that `date("G\:i", strtotime("6:00"));` will store the time of 6 hours and print out "6:00". I still need to figure out how to add them together.
