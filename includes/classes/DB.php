<?php


function conn() {
	$myHostName = "localhost"; #provide default DB credentials here
	$myUserName = "root";
	$myPassword = "root";
	$myDatabase = "itc280";
	
	//create mysqli improved connection
	$myConn = @mysqli_connect($myHostName, $myUserName, $myPassword, $myDatabase) or die(trigger_error(mysqli_connect_error(), E_USER_ERROR));
	
	return $myConn;
}



class DB { 
	private static $instance = null; #stores a reference to this class

	private function __construct() {
		#establishes a mysqli connection - private constructor prevents direct instance creation 
		$myHostName = "localhost";#provide default DB credentials here
		$myUserName = "root";
		$myPassword = "root";
		$myDatabase = "itc280";
		#hostname, username, password, database
		$this->dbHandle = mysqli_connect($myHostName,$myUserName, $myPassword, $myDatabase) or die(trigger_error(mysqli_connect_error(), E_USER_ERROR));
	} 

	public static function conn() {
		#Creates a single instance of the database connection
		if(self::$instance == null) {
			self::$instance = new self;
		}#only create instance if does not exist
		
		return(self::$instance->dbHandle);
    }
}

?>