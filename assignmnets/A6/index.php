<?php
function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }

include(findRoot() . 'run.php');

displayHead('Asssigment 6');

$sql = "select * from articles";

$myConn = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);

mysql_select_db(MYSQL_DATABASE, $myConn);

$result = mysql_query($sql, $myConn);

?>
<aticle>
<h1>Database A6</h1>
<p><strong>Below is the assignment and not the normal posts</strong> (Although all the links work):</p>
</aticle>
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
   			<h1><a href="/?a=' . linkText($row['type'] . ' ' . $row['title']) . '">' . getFullTitle($row['title'], $row['type']) . '</a></h1>
   			' . Markdown($row['body']). '
   			<span class="date-comments">' . date('l\, F jS Y', strtotime($row['date'])) . $timeSpentWord . ' - <a href="/?a=' . linkText($row['type'] . ' ' . $row['title']) . '#comments">' . 0 . ' Comments</a></span>
   		</article>');
    }
    
} else {
	//no records
    echo('<h3>What! No customers?  There must be a mistake!!</h3>');
}


displayCommentForm();
displayFooter();

function getFullTitle($title, $type) {

	$type = strtolower(str_replace(' ', '', $type));

	if ($type == 'resource') {
		return('Resource &#10093;&#10093 ' . $title);
	} elseif ($type == 'assignment') {
		return('Assignment ' . $title);
	} elseif ($type == 'extracredit') {
		return('Extra Credit ' . $title);
	} else {
		return('Title Error');
		
	}
}

@mysql_free_result($result); //releases web server memory. Remember that '@' turn off errors for that line

?>