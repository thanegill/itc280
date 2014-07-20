<?php
/**
 * $config->adminValidate.php validation page for access to administrative area
 *
 * Processes form data from $config->adminLogin.php to process administrator login requests.
 * Forwards user to admin.php, upon successful login. 
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see $config->adminLogin.php
 * @see admin.php
 * @todo none
 */

require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials  

if (isset($_POST['em']) && isset($_POST['pw'])) {
	//if POST is set, prepare to process form data
	//next check for specific issues with data
	if(!ctype_graph($_POST['pw']))
	{//data must be alphanumeric or punctuation only	
		feedback("Illegal characters were entered.");
		myRedirect(THIS_PAGE);
	}
	
	if(!onlyEmail($_POST['em']))
	{//login must be a legal email address only	
		feedback("Illegal characters were entered.");
		myRedirect(THIS_PAGE);
	}
	
	$myConn = conn("",FALSE); # mysql classic conn, MUST precede formReq() which uses active connection to parse data
	
	$redirect = $config->adminLogin; # global var used for following formReq redirection on failure
	$Email = formReq('em');# formReq()requires a form element with data, redirects to $redirect if no data sent 
	$MyPass = formReq('pw');# formReq() calls dbIn() internally, to check form data

	$sql = sprintf("select AdminID,FirstName,Privilege,NumLogins from " . PREFIX . "Admin WHERE Email='%s' AND AdminPW=SHA('%s')",$Email,$MyPass);
	$result = @mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
	if(mysql_num_rows($result) > 0) # had to be a match
	{# valid user, create session vars, redirect!
		
		$row = mysql_fetch_array($result); #no while statement, should be single record
		startSession(); #wrapper for session_start()
		$AdminID = (int)$row["AdminID"];  # use (int) cast to for conversion to integer
		$_SESSION["AdminID"] = $AdminID; # create session variables to identify admin
		$_SESSION["FirstName"] = dbOut($row["FirstName"]);  #use dbOut() to clean strings, replace escaped quotes
		$_SESSION["Privilege"] = dbOut($row["Privilege"]);
		$NumLogins = (int)$row["NumLogins"];
		$NumLogins+=1;  # increment number of logins, then prepare to update record!
		
		# update Admin record, recording new number of logins, and new LastLogin date/time
		$sql = sprintf("UPDATE " . PREFIX . "Admin set NumLogins=%d, LastLogin=NOW()  WHERE AdminID=%d",$NumLogins,$AdminID);
		@mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
		
		if(isset($_SESSION['red']) && $_SESSION['red'] != "")
		{#check to see if we'll be redirecting to a requesting page
			$red = $_SESSION['red']; #redirect back to original page
			$_SESSION['red'] == ''; #clear session var
			feedback("Login Successful!", "notice");
			myRedirect($red);
		}else{
			feedback("Login Successful!", "notice");
			myRedirect($config->adminDashboard);# successful login! Redirect to admin page
		} 
         
	}else{# failed login, redirect
	    feedback("Login and/or Password are incorrect.");
		myRedirect($config->adminLogin);
	}
}else{
	feedback("Required data not sent.");
	myRedirect($config->adminLogin);	
}		
?>
