<?php
/**
 * config_inc.php stores site-wide configuration settings, functions & file references
 * 
 * Stores configuration data like support email address, SUPPORT_EMAIL
 * and functions like my_error_handler() which over-rides the default error handler of PHP.
 *
 * There are references to include files common_inc.php, which stores utility functions 
 * and conn_inc.php which stores database credentials 
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.091 2011/06/17
 * @link http://www.newmanix.com/ 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see common_inc.php
 * @see conn_inc.php 
 * @todo none
 */
# START ERROR HANDLING (show or hide page errors, turn on/off error logging)---------------------------------------------
# We can un-comment the line below to either see default errors (1) or shut off visual errors completely (0). 
ini_set('error_reporting', E_ALL | E_STRICT);  # E_ALL | E_STRICT = currently tracking all errors & warnings
define('SHOW_ALL_ERRORS', TRUE); # TRUE = SHOW ALL SITE ERRORS - if FALSE must be logged in as ADMIN to view errors
define('LOG_ALL_ERRORS', TRUE); # TRUE = TRACK ALL ERRORS IN ERROR LOG FILE
loadErrorHandler('custom'); #can be set to 'custom', 'php' or 'none'
# END ERROR HANDLING (show or hide page errors, turn on/off error logging)-----------------------------------------------  
 
# START SETTINGS (php.ini overrides & other enviroment settings)---------------------------------------------------------
ob_start();  #buffers our page to be prevent header errors. Call before INC files or ANY html!
date_default_timezone_set('America/Los_Angeles'); #sets default date/timezone for this website
//ini_set('session.save_path','/home/classes/wgill002/sessions'); #optional folder set to 0700 outside webroot to store session data
header("Cache-Control: no-cache");header("Expires: -1");#Helps stop browser & proxy caching
$config = new Config(); #config object stores page properties, including <title> meta tag & nav elements
# END SETTINGS (php.ini overrides & other enviroment settings)------------------------------------------------------------ 

# START CONSTANTS (universal file paths & values)-------------------------------------------------------------------------
/* automatic path settings - use the following 4 path settings for placing all code in one application folder */ 
define('VIRTUAL_PATH', 'http://localhost/assignmnets/A7/sprockets/'); # Virtual (web) 'root' of application for images, JS & CSS files
define('PHYSICAL_PATH', '/Users/thanegill/Dropbox/ITC280/assignmnets/A7/sprockets/'); # Physical (PHP) 'root' of application for file & upload reference
define('INCLUDE_PATH', PHYSICAL_PATH . 'inc_0700/'); # Path to PHP include files - INSIDE APPLICATION ROOT
//define('INCLUDE_PATH', '/home/classes/horsey01/inc_sprockets/'); #Path to PHP include files - OUTSIDE WEB ROOT
define('LOG_PATH', INCLUDE_PATH . 'log/'); # Log files are stored in the PHP include folder
define('ADMIN_PATH', VIRTUAL_PATH . 'admin/'); # Admin files are in subfolder
define('SUPPORT_EMAIL', 'wgill002@seattlecentral.edu'); # Email of site support
define('PREFIX', ''); #Prefix to add uniqueness to your DB table names.  Limits hackability, naming collisions

define('ADMIN_LOGIN', ADMIN_PATH . 'admin_login.php'); # Admin login page - all Admin pages part of nmAdmin package
define('ADMIN_VALIDATE', ADMIN_PATH . 'admin_validate.php'); # Admin login validation page
define('ADMIN_DASHBOARD', ADMIN_PATH . 'admin_dashboard.php'); # Administrative (dashboard) page
define('ADMIN_LOGOUT', ADMIN_PATH . 'admin_logout.php'); # Administrative logout file
define('ADMIN_ADD', ADMIN_PATH . 'admin_add.php'); # Add administrators here
define('ADMIN_RESET', ADMIN_PATH . 'admin_reset.php'); # Reset admin passwords here
define('ADMIN_EDIT', ADMIN_PATH . 'admin_edit.php'); # Edit admin data here
define('TABLE_EDITOR', ADMIN_PATH . 'nmEdit.php'); # Table Editor part of nmEdit package
define('THIS_PAGE', basename($_SERVER['PHP_SELF'])); # Current page name, stripped of folder info - (saves resources)
# END CONSTANTS (universal file paths & values)----------------------------------------------------------------------------

# START INCLUDES (declare universal file paths & values)-------------------------------------------------------------------
include INCLUDE_PATH . 'conn_inc.php'; # Provides database connectivity - part of nmCommon package
include INCLUDE_PATH . 'common_inc.php'; # Provides common utility functions - part of nmCommon package
include INCLUDE_PATH . 'custom_inc.php'; # Provides spot for custom utility functions - part of nmCommon package
# END INCLUDES (declare universal file paths & values)---------------------------------------------------------------------

# CONTENT CONFIGURATION AREA (theme, content areas & nav arrays for header/footer )-----------------------------------------
$config->theme = 'TwoTone'; #default theme (header/footer combo) three included themes are 'TwoTone', 'SmallPark' and 'Noodles'
$config->titleTag = 'Cotlets Website';
$config->metaDescription = 'Welcome to the Cotlets website.  We split off from Applets.  But We are better.';
$config->metaKeywords = 'Cotlets,Apricots,Turkish Delight,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->banner = 'Cotlets R Us'; #goes inside header - can be overwritten
$config->copyright = 'Cotlets, Not Applets, &copy; 2008 - ' . date('Y'); #goes inside footer - can be overwritten

$config->sidebar1 = '
<h3 align="center">Sidebar 1</h3>
';
$config->sidebar2 = '<h3 align="center">Sidebar 2</h3>'; #sidebars can be overwritten (or added to) in individual pages
if(startSession() && isset($_SESSION['AdminID']))
{#add admin logged in info to sidebar
	$config->sidebar2 .= '<p align="center">' . $_SESSION['Privilege'] . ' <b>' . $_SESSION['FirstName'] . '</b> is logged in.</p>';
	$config->sidebar2 .= '<p align="center"><a href="' . ADMIN_DASHBOARD . '">ADMIN</a></p>';
	$config->sidebar2 .= '<p align="center"><a href="' . ADMIN_LOGOUT . '">LOGOUT</a></p>';
}	
$config->sidebar2 .= '
<p>Here is our sidebar area which is inside a header or footer include file. You can change it in the main config file or 
change it on a page by page basis by altering config settings inside individual pages.</p> 
';
// DECLARING ARRAY IN ADVANCE SHOULD BE UNNECESSARY - TRIP HAZARD!!$nav1 = array(); #nav1 is the current left nav - tilde separator below splits text of link from title attribute
if(startSession() && isset($_SESSION['AdminID'])){$nav1[ADMIN_DASHBOARD] = "ADMIN~Go to Administrative Page";}#admin page added to link only if logged in
$nav1['index.php'] = "Home~A model for building largely static web pages";
$nav1['demo/demo_contact.php'] = "Contact~A demo for building postback forms";
$nav1['demo/demo_mysql.php'] = "mysql Classic~A demo for building mysql (classic) web applications";
$nav1['demo/demo_mysqli.php'] = "mysqli Improved~A demo for building mysqli (improved) web applications";
$nav1['demo/demo_shared.php'] = "MySQL Shared~A demo page for building mysqli shared connection based applications.";
$nav1['error_test.php'] = "Error Test~Click to see how errors are currently being handled";
$config->nav1 = $nav1;  #add to global config object - now available in all header/footers
# CONTENT CONFIGURATION AREA (theme, content areas & nav arrays for header/footer )-----------------------------------------

# GENERAL CONFIG AREA ENDS HERE ############################################################################################

# START FUNCTIONS (error handling/logging functions)------------------------------------------------------------------------ 
/**
 * Allows developer choice of 'custom', 'php' or 'none' for error handler. 
 * Overrides PHP's default error handler, if 'custom' is chosen.  This function 
 * in turn calls myErrorHandler().
 *
 * Inherits error & logging info from default handler and allows us to display
 * custom error messages based on the following boolean constants:
 *
 * 1 SHOW_ALL_ERRORS 
 * 2 LOG_ALL_ERRORS
 *   
 * @param string $handler 'custom', 'php' or 'none' for error handler
 * @return void
 * @uses myErrorHandler() 
 * @todo threshold email of admin not implemented
 */
function loadErrorHandler($handler)
{
	$handler = strtolower($handler);
	switch($handler)
	{
		case 'custom':
			set_error_handler ('myErrorHandler'); # Override the default PHP error handler with our own.
			break;
		case 'php';
			ini_set('display_errors', 1); # 1 = display PHP errors
			if(LOG_ALL_ERRORS)
			{
				ini_set('log_errors', 1); # 1 turns on error logging, 0 shuts it off
				ini_set('error_log', LOG_PATH . 'error_log'); #places PHP errors into a folder at this location	
			}
			break;		
		default:
			ini_set('display_errors', 0); # 0 turns off PHP error reporting entirely
			if(LOG_ALL_ERRORS)
			{
				ini_set('log_errors', 1); # 1 turns on error logging, 0 shuts it off
				ini_set('error_log', LOG_PATH . 'error_log'); #places PHP errors into a folder at this location	
			}			
	}	
}# End loadErrorHandler()

/**
 * Overrides PHP's default error handler.  This function is enabled/disabled 
 * in the loadErrorHandler() function
 *
 * Inherits error & logging info from default handler and allows us to display
 * custom error messages based on the following boolean constants:
 *
 * 1 SHOW_ALL_ERRORS 
 * 2 LOG_ALL_ERRORS
 *   
 * @param string $e_number error number provided by PHP error handler
 * @param string $e_message error message provided by PHP error handler
 * @param string $e_file file name provided by PHP error handler
 * @param string $e_line line number of error provided by PHP error handler
 * @param array $e_vars variables present at time of error 
 * @return void
 * @see loadErrorHandler() 
 * @todo in production, send email to admin if threshold number/type of errors is exceeded
 */
function myErrorHandler ($e_number, $e_message, $e_file, $e_line, $e_vars)
{
	#comment out one of the two $errFile variables - one for a single file, one for a new file every day
	$errFile =  'error_log_' . date('m-d-Y') . '.txt'; #new error file created every day
	#$errFile =  'error_log'; # same single error log file as PHP will write errors
	static $counter = 0; # Will identify if myError() was called more than once
	$counter++;
	if(LOG_ALL_ERRORS)
	{# Copy all error info to error log file
		try	{
	    	$errInfo = "[" . date('M-d-Y G:i:s') . "] $e_message in $e_file on line $e_line" . "\n";
		    fileWrite(LOG_PATH . $errFile,'a+',$errInfo);
			
			if(substr(sprintf('%o', fileperms(LOG_PATH . $errFile)), -4)!= '0700')
			{//check file permission - if NOT set to 0700, fix it!
				chmod(LOG_PATH . $errFile,0700);
			}
		} catch (Exception $e) {
		    //echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	if (SHOW_ALL_ERRORS || isset($_SESSION['AdminID']))
	{# Display generic error message, with support email from config file
		printDeveloperError($e_file,$e_line,$e_message,$counter); 
	}else{# Show errors directly on page.  (troubleshooting purposes only!)
		if($counter < 2) { printUserError($e_file,$e_line); } #only print one error message to user
	}
}# End myErrorHandler()

/**
 * Create an error code out of the file name and line number of our error
 *
 * Will make upper case, strip out the vowels and create an 
 * error of the file name (minus extension & vowels) + "x" + line number of error
 *
 * Will also replace any underscores with "x". This file would be:
 *
 * Example: CNFGxNCx41
 * 
 * The above would be the example for this file, plus an error at line 41
 * This allows a user to report an error that identifies it, without compromising site security
 *
 * @param string $myFile file name provided by PHP error handler
 * @param string $myLine line number of error provided by PHP error handler
 * @return string File name and line number of error disguised vaguely as an error code
 * @see printUserError() 
 * @todo none
 */
function createErrorCode($myFile,$myLine)
{
	$mySlash = strrpos($myFile,"/"); //find position of last slash in path
	$myFile = substr($myFile,$mySlash + 1);  //strip off all but file name
	$myFile = substr($myFile, 0, strripos($myFile, '.'));//remove extension
	$myFile = strtoupper($myFile); //change to upper case
	$vowels = array("A", "E", "I", "O", "U", "Y");  //array of vowels to remove
	$myFile = str_replace($vowels, "", $myFile); //remove vowels
	$myFile = str_replace("_", "x", $myFile); //replace underscore with "x"
	return $myFile . "x" . $myLine;  //CNFGNCx50
}# End createErrorCode()

/**
 * Prints a customized public error message
 *
 * Will use a custom error code created by calling 
 * createErrorCode() function, and display it to the user
 *  
 * @param string $myFile file name provided by PHP error handler
 * @param string $myLine line number of error provided by PHP error handler
 * @return void
 * @see createErrorCode()
 * @see printDeveloperError()  
 * @todo none
 */
function printUserError($myFile,$myLine)
{
	$errorCode = createErrorCode($myFile,$myLine); //Create error code out of file name & line number
	echo '<h2 align="center">Our page has encountered an error!</h2>';
	echo '<table align="center" width="50%" style="border:#F00 1px solid;"><tr><td align="center">';
	echo 'Please try again, or email support at <b>' . SUPPORT_EMAIL . '</b>,<br /> and let us know you are receiving ';
	echo 'the following Error Code: <b>' . $errorCode . '</b><br />';
	echo 'This will help us identify the problem, and fix it as quickly as possible.<br />';
	echo 'Thank you for your assistance and understanding!<br />';
	echo 'Sincerely,<br />Support Staff<br />';
	echo '<a href="index.php">Exit</a></td></tr></table>';
	get_footer(); #add footer info!
	die(); #one error is enough!
}# End printUserError()

/**
 * Prints a customized developer error message
 *
 * This is what a developer will see when an error occurs
 *  
 * @param string $myFile file name provided by PHP error handler
 * @param string $myLine line number of error provided by PHP error handler
 * @param string $errorMsg error text provided by mysql_error() or developer, etc.
 * @param array|string $vars array dump of page variables, if available  
 * @return void
 * @see printUserError() 
 * @todo none
 */
function printDeveloperError($myFile,$myLine,$errorMsg,$counter)
{
	# Build the error message.
	echo '<div class="error">';  # No body or closing HTML allows multiple errors to show
	echo 'Error in file: <b>\'' . $myFile . '\'</b> on line: <font color="blue"><b>' . $myLine . '</b></font> '; 
	echo 'Error message: <font color="red"><b>' . $errorMsg . '</b></font><br /><br />';
	
	 #only print one instance of backtrace of debug data:
	if($counter < 2) { echo 'BackTrace: <font color="purple"><pre>' . print_r(debug_backtrace(),1) . '</pre></font><br /><br />'; }
	echo '</div><br />'; 
	
}# End printDeveloperError()

/**
 * Encapsulates PHP write capability for functions like errorLog()  
 *
 * <code>
 * return fileWrite("path/tomyfile.php","a+","Here is a test string!");	
 * </code>
 *
 * @param string $filename The target file to be written
 * @param string $myMode context we use to write file, a+ is append/create, a is append, w is write/overwrite
 * @param string $myStr The string to write to the file
 * @return boolean returns true or false, success of writing file
 * @see errorLog() 
 * @todo none
 */
function fileWrite($fileName,$myMode,$myStr)
{
$isOpen = fopen($fileName,$myMode);
  if($isOpen)
  {
        fwrite($isOpen,$myStr);
        fclose($isOpen);
        return TRUE;
  }else{
        return FALSE;
  }
}#End fileWrite()

# END FUNCTIONS (error handling/logging functions)--------------------------------------------------------------------------- 

# CLASS AREA (helper classes)------------------------------------------------------------------------------------------------
/**
 * Allows configuration properties to be added on the fly, for example navigation
 * elements can now be globally available as properties of the main configuration object
 */
 
$config->loadhead = '';  #would not load in constructor, so loading here!
if(!isset($config->benchmarking)){$config->benchmarking = FALSE;}
if(!isset($config->timer)){$config->timer = '';}
 
class Config {
    private $data = array();//Location for overloaded data.

	public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function &__get($name) {//& is required for pass by reference on arrays
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function __isset($name) {
        return isset($this->data[$name]);
    }
    
    public function __unset($name) {
        unset($this->data[$name]);
    }
}
# CLASS AREA (helper classes)----------------------------------------------------------------------------------------------------
#no closing PHP tag, on purpose