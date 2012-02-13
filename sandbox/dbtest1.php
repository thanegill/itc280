<?php
//dbtest1.php

error_reporting(E_ALL);
ini_set('display_errors','On');

//echo(ini_set('display_errors','On')); //Check to see if errors are turned on.

include('credentials.php');
include('../includes/util.php');
include('../includes/markdown.php');

$sql = "select * from Article";

//Connect to mysql
$myConn = mysql_connect($myHostName, $myUserName, $myPassword);

//Connect to database
mysql_select_db($myDatabase, $myConn);

//Apply sql statment
$result = mysql_query($sql, $myConn);

//Loop through data and show on page
while($row=mysql_fetch_assoc($result)) {
	//pull data from array
	echoRow('id');
	echoRow('date');
	echoRow('type');
	echoRow('title');
	echoRow('timeSpent');
	echoRow('link');
	echo('body: ' . markdown($row['body'] . '<hr>') . '<br />');
	echo('<br/><br/>');
}

function echoRow($str) {
	global $row;
	echo($str . ': ' . $row[$str] . '<br />');
}

?>