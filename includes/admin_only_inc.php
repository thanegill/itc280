<?php
/**
 * admin_only_inc.php session protection include for restricting access to administrative areas
 *
 * Checks for AdminID session variable, and forcibly redirects users not logged in
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.0 2010/08/04
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see admin_login.php
 * @see admin.php 
 * @todo none
 */

startSession(); # wrapper for session_start()

if(!isset($_SESSION['AdminID'])) { //no session var
	$myProtocol = strtolower($_SERVER["SERVER_PROTOCOL"]); # Cascade the http or https of current address
	if(strrpos($myProtocol,"https")>-1){$myProtocol = "https://";}else{$myProtocol = "http://";}
	$myURL = $_SERVER['REQUEST_URI'];  #Path derives properly on Windows & UNIX. alternatives: SCRIPT_URL, PHP_SELF
	$_SESSION['red'] = $myProtocol . $_SERVER['HTTP_HOST'] . $myURL;
     myRedirect(ADMIN_LOGIN . "?msg=5");
} else {
	if(!is_numeric($_SESSION['AdminID'])){myRedirect(ADMIN_LOGIN . "?msg=5");}
	if(!isset($access)|| $access == ""){$access = "admin";}//empty becomes admin 
	$access = strtolower($access); //in case of typo
	switch($access)	{
		case "admin": 
			break;
		case "superadmin":
			# not developer/superadmin, back to admin page
			if($_SESSION['Privilege']!="developer" && $_SESSION['Privilege']!="superadmin"){myRedirect(ADMIN_DASHBOARD . "?msg=1");}
			break;
		case "developer": //highest level. all access!
			# not developer to admin page
			if($_SESSION['Privilege']!="developer"){myRedirect(ADMIN_DASHBOARD . "?msg=1");}
			break;		
		break;
	}
}	
?>
