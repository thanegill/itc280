Title: Six &#10093;&#10093; First DB
Type: assignment
Date: 2012-02-17
Time Spent: 30


[Assignment 6 First DB](/assignmnets/A6/)

I took my current articles as my content for the database then rewrote my current `displayArticle()` function to play nice with MySQL.

	<?php
	if (mysql_num_rows($result) > 0) {
	//at least one record!
	//show results
	
	    while ($row = mysql_fetch_assoc($result)) {
	
	   		//Should I display $timeSpent?
	   		if (!isset($row['timeSpent']) || $row['timeSpent'] == null || $row['timeSpent'] == "" || $row['timeSpent'] == 'none' || $row['timeSpent'] == 0) {
	   			$timeSpentWord = "";
	   		} else {
	   			$timeSpentWord = " - Time Spent: " . displayTimeSpent($row['timeSpent']);
	   		}
	
	   		echo('<article>
	   			<h1><a href="/posts/?p=' . linkText($row['type'] . ' ' . $row['title']) . '">' . getFullTitle($row['title'], $row['type']) . '</a></h1>
	   			' . Markdown($row['body']). '
	   			<span class="date-comments">' . date('l\, F jS Y', strtotime($row['date'])) . $timeSpentWord . ' - <a href="/posts/?p=' . linkText($row['type'] . ' ' . $row['title']) . '#comments">' . 0 . ' Comments</a></span>
	   		</article>');
	    }
	    
	} else {
		//no records
	    echo('<h3>What! No customers? There must be a mistake!!</h3>');
	}
	?>
