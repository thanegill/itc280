<?php

function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }

include(findRoot() . 'run.php');

##################################################################
# START SETTINGS (php.ini overrides & other enviroment settings) #
##################################################################

ob_start(); #buffers our page to be prevent header errors. Call before INC files or ANY html!
//date_default_timezone_set('America/Los_Angeles'); #sets default date/timezone for this website
//ini_set('session.save_path','/home/classes/horsey01/sessions'); #optional folder set to 0700 outside webroot to store session data

header('Cache-Control: no-cache');
header('Expires: -1'); #Helps stop browser & proxy caching
$config = new stdClass; #standard class allows dynamic property assignment - used to store data across themes


###########################################################
# START CONSTANTS & PATHS (universal file paths & values) #
###########################################################

# Automatic path settings - use the following 4 path settings for placing all code in one application folder
define('VIRTUAL_PATH', 'http://itc280.psophis.com/'); # Virtual (web) 'root' of application for images, JS & CSS files
define('PHYSICAL_PATH', findRoot()); # Physical (PHP) 'root' of application for file & upload reference
define('INCLUDE_PATH', PHYSICAL_PATH . 'includes/'); # Path to PHP include files - INSIDE APPLICATION ROOT
define('LOG_PATH', INCLUDE_PATH . 'log/'); # Log files are stored in the PHP include folder
define('ADMIN_PATH', VIRTUAL_PATH . 'admin/'); # Admin files are in subfolder

define('SUPPORT_EMAIL', 'me@thanegill.com'); # Email of site support
define('PREFIX', ''); #Prefix to add uniqueness to your DB table names. Limits hackability, naming collisions.
define('THIS_PAGE', basename($_SERVER['PHP_SELF'])); # Current page name, stripped of folder info - (saves resources)

$config->adminLogin = ADMIN_PATH . 'admin_login.php'; # Admin login page - all Admin pages part of nmAdmin package
$config->adminValidate = ADMIN_PATH . 'admin_validate.php'; # Admin login validation page
$config->adminDashboard = ADMIN_PATH . 'admin_dashboard.php'; # Administrative (dashboard) page
$config->adminLogout = ADMIN_PATH . 'admin_logout.php'; # Administrative logout file
$config->adminAdd = ADMIN_PATH . 'admin_add.php'; # Add administrators here
$config->adminReset = ADMIN_PATH . 'admin_reset.php'; # Reset admin passwords here
$config->adminEdit = ADMIN_PATH . 'admin_edit.php'; # Edit admin data here
$config->tableEditor = ADMIN_PATH . 'nmEdit.php'; # Table Editor part of nmEdit package


############################################
# START INCLUDES (reference include files) #
############################################

include INCLUDE_PATH . 'conn_inc.php'; # Provides database connectivity - part of nmCommon package
include INCLUDE_PATH . 'common_inc.php'; # Provides common utility functions - part of nmCommon package


#####################################################################################
# CONTENT CONFIGURATION AREA (theme, content areas & nav arrays for header/footer ) #
#####################################################################################

$config->theme = 'linen'; #default theme (header/footer combo) three included themes are 'TwoTone', 'SmallPark' and 'Noodles'
$config->titleTag = SITE_NAME;

$config->banner = SITE_NAME; #goes inside header - can be overwritten

if(startSession() && isset($_SESSION['AdminID'])) {
	#add admin logged in info to sidebar
	$config->sidebar2 .= '<p align="center">' . $_SESSION['Privilege'] . ' <b>' . $_SESSION['FirstName'] . '</b> is logged in.</p>';
	$config->sidebar2 .= '<p align="center"><a href="' . $config->adminDashboard . '">ADMIN</a></p>';
	$config->sidebar2 .= '<p align="center"><a href="' . $config->adminLogout . '">LOGOUT</a></p>';
}

$config->sidebar2 .= '<p>Here is our sidebar area which is inside a header or footer include file. You can change it in the main config file or change it on a page by page basis by altering config settings inside individual pages.</p>';
// DECLARING ARRAY IN ADVANCE SHOULD BE UNNECESSARY - TRIP HAZARD!!$nav1 = array(); #nav1 is the main navigation - tilde separator below splits text of link from title attribute

if(startSession() && isset($_SESSION['AdminID'])) {
	#admin page added to link only if logged in
	$nav1[$config->adminDashboard] = "ADMIN~Go to Administrative Page";
}

##################################
# DEFAULT EMPTY DATA STARTS HERE #
##################################

$config->loadhead = ''; #can be used to add js & css to bottom of head tag
$config->benchmarking = '';

#####################################################################
# START ERROR HANDLING FUNCTIONS (error handling/logging functions) #
#####################################################################

/**Allows developer choice of 'custom', 'php' or 'none' for error handler
 *
 * Overrides PHP's default error handler, if 'custom' is chosen
 *
 * This function in turn calls the myErrorHandler() function
 *
 * Inherits error & logging info from default handler and allows us to display
 * custom error messages based on the following boolean constants:
 *
 * 1 SHOW_ALL_ERRORS 
 * 2 LOG_ALL_ERRORS
 *   
 * @param string $handler 'custom', 'php' or 'none' for error handler
 * @return void
 */
function loadErrorHandler($handler) {
	$handler = strtolower($handler);
	global $config;
	switch($handler){
		case 'custom':
			set_error_handler ('myErrorHandler'); # Override the default PHP error handler with our own.
			break;
		case 'php';
			ini_set('display_errors', 1); # 1 = display PHP errors
			error_reporting($config->error_reporting);
			if(LOG_ALL_ERRORS) {
				ini_set('log_errors', 1); # 1 turns on error logging, 0 shuts it off
				ini_set('error_log', LOG_PATH . 'error_log'); #places PHP errors into a folder at this location	
			}
			break;		
		default:
			ini_set('display_errors', 0); # 0 turns off PHP error reporting entirely
			if(LOG_ALL_ERRORS) {
				ini_set('log_errors', 1); # 1 turns on error logging, 0 shuts it off
				ini_set('error_log', LOG_PATH . 'error_log'); #places PHP errors into a folder at this location	
			}			
	}	
}

/**Overrides PHP's default error handler.  This function is enabled/disabled 
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
 */
function myErrorHandler ($e_number, $e_message, $e_file, $e_line, $e_vars) {
	#comment out one of the two $errFile variables - one for a single file, one for a new file every day
	$errFile =  'error_log_' . date('m-d-Y') . '.txt'; #new error file created every day
	#$errFile =  'error_log'; # same single error log file as PHP will write errors
	static $counter = 0; # Will identify if myError() was called more than once
	$counter++;
	if(LOG_ALL_ERRORS) {
		# Copy all error info to error log file
		try	{
	    	$errInfo = "[" . date('M-d-Y G:i:s') . "] $e_message in $e_file on line $e_line" . "\n";
		    fileWrite(LOG_PATH . $errFile,'a+',$errInfo);
			
			if(substr(sprintf('%o', fileperms(LOG_PATH . $errFile)), -4)!= '0700') {
				//check file permission - if NOT set to 0700, fix it!
				chmod(LOG_PATH . $errFile, 0700);
			}
		} catch (Exception $e) {
		    //echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	if (SHOW_ALL_ERRORS || isset($_SESSION['AdminID'])) {
		# Display generic error message, with support email from config file
		printDeveloperError($e_file, $e_line,$e_message,$counter); 
	} else {# Show errors directly on page.  (troubleshooting purposes only!)
		if($counter < 2) {
			printUserError($e_file, $e_line);
		}
		#only print one error message to user
	}
}

/**Create an error code out of the file name and line number of our error
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
 */
function createErrorCode($myFile, $myLine) {
	$mySlash = strrpos($myFile, "/"); //find position of last slash in path
	$myFile = substr($myFile, $mySlash + 1);  //strip off all but file name
	$myFile = substr($myFile, 0, strripos($myFile, '.'));//remove extension
	$myFile = strtoupper($myFile); //change to upper case
	$vowels = array("A", "E", "I", "O", "U", "Y");  //array of vowels to remove
	$myFile = str_replace($vowels, "", $myFile); //remove vowels
	$myFile = str_replace("_", "x", $myFile); //replace underscore with "x"
	return $myFile . "x" . $myLine;  //CNFGNCx50
}

/**Prints a customized public error message
 *
 * Will use a custom error code created by calling 
 * createErrorCode() function, and display it to the user
 *  
 * @param string $myFile file name provided by PHP error handler
 * @param string $myLine line number of error provided by PHP error handler
 * @return void
 */
function printUserError($myFile, $myLine) {
	$errorCode = createErrorCode($myFile, $myLine); //Create error code out of file name & line number
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
}

/**Prints a customized developer error message
 *
 * This is what a developer will see when an error occurs
 *  
 * @param string $myFile file name provided by PHP error handler
 * @param string $myLine line number of error provided by PHP error handler
 * @param string $errorMsg error text provided by mysql_error() or developer, etc.
 * @param array|string $vars array dump of page variables, if available  
 * @return void
 */
function printDeveloperError($myFile, $myLine, $errorMsg, $counter) {
	# Build the error message.
	echo '<div class="error">';  # No body or closing HTML allows multiple errors to show
	echo 'Error in file: <b>\'' . $myFile . '\'</b> on line: <font color="blue"><b>' . $myLine . '</b></font> '; 
	echo 'Error message: <font color="red"><b>' . $errorMsg . '</b></font><br /><br />';
	
	 #only print one instance of backtrace of debug data:
	if($counter < 2) { echo 'BackTrace: <font color="purple"><pre>' . print_r(debug_backtrace(),1) . '</pre></font><br /><br />'; }
	echo '</div><br />'; 
	
}

/**Encapsulates PHP write capability for functions like errorLog()  
 *
 * <code>
 * return fileWrite("path/tomyfile.php","a+","Here is a test string!");	
 * </code>
 *
 * @param string $filename The target file to be written
 * @param string $myMode context we use to write file, a+ is append/create, a is append, w is write/overwrite
 * @param string $myStr The string to write to the file
 * @return boolean returns true or false, success of writing file
 */
function fileWrite($fileName, $myMode, $myStr) {
	$isOpen = fopen($fileName, $myMode);
	if($isOpen){
	    fwrite($isOpen,$myStr);
	    fclose($isOpen);
	    return TRUE;
	} else {
	    return FALSE;
	}
}
