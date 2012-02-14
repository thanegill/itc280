<?php
function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }

include(findRoot() . 'run.php');

displayHead('Template');

$sql = "select * from test_Customers";

$myConn = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);

mysql_select_db(MYSQL_DATABASE, $myConn);

$result = mysql_query($sql, $myConn);

?>
<h1>Database A6</h1>
<?php

if (mysql_num_rows($result) > 0) {//at least one record!
//show results
    while ($row = mysql_fetch_assoc($result)) {
       echo("<p>");
       echo("FirstName: <b>" . $row['FirstName'] . "</b><br />");
       echo("LastName: <b>" . $row['LastName'] . "</b><br />");
       echo("Email: <b>" . $row['Email'] . "</b><br />");
       echo("</p>");
    }
} else {//no records
    echo('<div align="center">What! No customers?  There must be a mistake!!</div>');
}


@mysql_free_result($result); //releases web server memory. Remember that '@' turn on errors for that line

displayCommentForm();
displayFooter();
?>