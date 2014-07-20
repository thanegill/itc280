<?php
/**
 * admin_error_view.php works with admin_error_list.php to 
 * view & delete log files 
 *
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * admin_error_list.php
 * @todo none
 */
 
require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials

//END CONFIG AREA ----------------------------------------------------------

$access = "admin"; #admins can edit themselves, developers can edit any - don't change this var or no one can edit their own data
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var

if(isset($_GET['del']))
{#prepare to delete log file
	$myDelete = (trim($_GET['del']));
	unlink(LOG_PATH . $myDelete);  #deletes file
	myRedirect(ADMIN_PATH . 'admin_error_list.php?msg=1'); #redirect back to list page with message
}

if(isset($_GET['f']))
{#file info from qstring
	$fileName = (trim($_GET['f']));
	$filePath = LOG_PATH . $_GET['f'];
}else{
	$fileName = "No File Found";
}

$config->loadhead = '
<script language="JavaScript">
function confirmDelete()
{
	var agree=confirm("Are you sure you wish to delete this log file?");
	if(agree)
	{
		location.href="' . ADMIN_PATH . 'admin_error_view.php?del=' . $fileName . '";
	}else{
		return false;
	}
}	
</script>
';
get_header(); #defaults to theme header or header_inc.php
?>
<h1 align="center"><?php echo THIS_PAGE; ?></h1>

<?php
echo '<div align="center"><b>' . $fileName . '</b></div>';
echo '<div align="center"><a href="' . ADMIN_PATH . 'admin_error_list.php">back to list</a></div>';

if($fileName != "No File Found")
{#show log file contents - reading backwards to see most recent at top
	$myArray = file($filePath);
	if ($myArray != FALSE)
	{
	   for($x=count($myArray) - 1;$x>=0;$x--)
	   {
	      print $myArray[$x] . "<br />";
	   }
	}else{
	   print "No info in file";
	}
	echo '<div align="center"><a href="javascript:void(0);" onclick="return confirmDelete();">DELETE (no undo)</a></div>';
}
get_footer(); #defaults to theme footer or footer_inc.php
?>
