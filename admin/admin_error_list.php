<?php
/**
 * admin_error_list.php works with admin_error_view.php to 
 * view & delete log files 
 *
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see admin_error_view.php 
 * @todo none
 */
 
require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials

//END CONFIG AREA ----------------------------------------------------------
get_header(); #defaults to theme header or header_inc.php
?>
<h3 align="center"><?php echo THIS_PAGE ?></h3>
<?php

if(isset($_GET['msg']))
{//feedback is provided - perhaps data was entered improperly
	switch($_GET['msg'])
	{
		case 1:
			$feedback = "error log file deleted";
			break;
		default:
			$feedback = "";	
	}	
}else{//no feedback
	$feedback = "";	
}
if($feedback != ""){echo '<div align="center"><h3><font color="red">' . $feedback . '</font></h3></div>';} #Fill out feedback HTML	
$dir = opendir(LOG_PATH);#open log directory
$foundFile = FALSE;
echo '<ul>';

while ($read = readdir($dir))
{#read each file that is not a pointer to other folders
	if ($read!='.' && $read!='..')
	{#create a link to view each file
		echo '<li><a href="' . 	ADMIN_PATH  . 'admin_error_view.php?f=' . $read . '">' . $read . '</a></li>';
		$foundFile = TRUE;
	}
}
echo '</ul>';

if(!$foundFile){
	echo '<div align="center"><h3><font color="red">No log files found</font></h3></div>';		
}

closedir($dir);#close log folder 

get_footer(); #defaults to theme footer or footer_inc.php
?>
