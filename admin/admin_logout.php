<?php
/**
 * $config->adminLogout.php destroys session so administrators can logout
 *
 * Clears session data, forwards user to admin login page upon successful logout  
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/ 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see $config->adminLogin.php
 * @see $config->adminValidate.php
 * @todo none
 */

require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials 

startSession(); //wrapper for session_start()
$_SESSION = array();# Setting a session to an empty array safely clears all data

//session_destroy();# can't destroy session as will disable feedback
feedback("Logout Successful!", "notice");
myRedirect($config->adminLogin); # redirect for successful logout
?>
