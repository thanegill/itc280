<?php
/**
 * $config->adminReset.php allows an administrator to reset (reselect) a password 
 *
 * Because passwords are encrypted via the MySQL encrpyption SHA() method, 
 * we can't recover them, so we instead create new ones.
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/  
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see $config->adminAdd.php 
 * @see $config->adminEdit.php
 * @todo Currently the JS file is hard wired to a folder named 'include' inside 
 * $config->adminAdd.php & $config->adminReset.php.  Please change this path in these files until this is fixed.
 */

require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials
//END CONFIG AREA ---------------------------------------------------------- 
 
$access = "admin"; #admin can reset own password, superadmin can reset others
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var

get_header(); #defaults to theme header or header_inc.php

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}
switch ($myAction) 
{//check for type of process
	case "edit": //2) show password change form
	 	editDisplay();
	 	break;
	case "update": //3) change password, feedback to user
		updateExecute();
		break; 
	default: //1)Select Administrator
	 	selectAdmin();
}
get_footer(); #defaults to theme footer or footer_inc.php

function selectAdmin()
{//Select administrator
	global $config;
	echo '<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm)
			{//check form data for valid info
				if(empty(thisForm.AdminID,"Please Select an Administrator.")){return false;}
				return true;//if all is passed, submit!
			}
	</script>
	<h1>Reset Administrator Password</h1>';
	if($_SESSION["Privilege"] != "admin")
	{# must be greater than admin level to have  choice of selection
		echo '<p align="center">Select an Administrator, to reset their password:</p>';
	}
	echo '<form action="' . $config->adminReset . '" method="post" onsubmit="return checkForm(this);">';
	$myConn = conn('',FALSE);
	$sql = "select AdminID,FirstName,LastName,Email,Privilege,LastLogin,NumLogins from " . PREFIX . "Admin";
	if($_SESSION["Privilege"] == "admin")
	{# limit access to the individual, if admin level
		$sql .= " where AdminID=" . $_SESSION["AdminID"];
	}
	$result = @mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
	if (mysql_num_rows($result) > 0)//at least one record!
	{//show results
		echo '<form action="' . $config->adminReset . '" method="post" onsubmit="return checkForm(this);">';
		echo '<table align="center" border="1" style="border-collapse:collapse" cellpadding="3" cellspacing="3">';
		echo '<tr><th>AdminID</th><th>Admin</th><th>Email</th><th>Privilege</th></tr>';
		while ($row = mysql_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     echo '<tr><td>';
		     echo '<input type="radio" name="AdminID" value="' . dbOut($row['AdminID']) . '">';
		     echo dbOut($row['AdminID']) . '</td>';
		     echo '<td>' . dbOut($row['FirstName']) . ' ' . dbOut($row['LastName']) . '</td>';
		     echo '<td>' . dbOut($row['Email']) . '</td>';
		     echo '<td>' . dbOut($row['Privilege']) . '</td></tr>';
		}
		echo '<input type="hidden" name="act" value="edit" />';
		echo '<tr><td align="center" colspan="4"><input type="submit" value="Choose Admin!"></em></td></tr>';
		echo '</table></form>';	
	}else{//no records
      //put links on page to reset form, exit
      echo '<div align="center"><h3>Currently No Administrators in Database.</h3></div>';
	}
	 echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';
	@mysql_free_result($result); //free resources
}

function editDisplay()
{
	global $config;
	if(isset($_POST['AdminID']) && (int)$_POST['AdminID'] > 0)
	{
	 	$myID = (int)$_POST['AdminID']; #Convert to integer, will equate to zero if fails
	}else{
		feedback("AdminID not numeric");
		myRedirect($config->adminReset);
	}
	$myConn = conn('',FALSE);
	$sql = sprintf("select AdminID,FirstName,LastName,Email,Privilege from " . PREFIX . "Admin WHERE AdminID=%d",$myID);
	$result = @mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
	if(mysql_num_rows($result) > 0)//at least one record!
	{//show results
		while ($row = mysql_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     $Name = dbOut($row['FirstName']) . ' ' . dbOut($row['LastName']);
		     $Email = dbOut($row['Email']);
		     $Privilege = dbOut($row['Privilege']);
		}
	}else{//no records
      //put links on page to reset form, exit
      echo '<div align="center"><h3>No such administrator.</h3></div>';
      echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';
	}
	?>
	<script type="text/javascript" src="<?php echo VIRTUAL_PATH; ?>include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm)
			{//check form data for valid info
				if(!isAlphanumeric(thisForm.PWord1,"Only alphanumeric characters are allowed for passwords.")){thisForm.PWord2.value="";return false;}
				if(!correctLength(thisForm.PWord1,6,20,"Password does not meet the following requirements:")){thisForm.PWord2.value="";return false;}
				if(thisForm.PWord1.value != thisForm.PWord2.value)
				{//match password fields
	   			alert("Password fields do not match.");
	   			thisForm.PWord1.value = "";
	   			thisForm.PWord2.value = "";
	   			thisForm.PWord1.focus();
	   			return false;
	   		}
				return true;//if all is passed, submit!
			}
	</script>
	<h1>Reset Administrator Password</h1>
	<p align="center">Admin: <font color="red"><b><?php echo $Name;?></b></font> 
	Email: <font color="red"><b><?=$Email;?></b></font> Privilege: <font color="red"><b><?=$Privilege;?></b></font></p> 
	<p align="center">Be sure to write down password!!</p>
	<form action="<?=$config->adminReset;?>" method="post" onsubmit="return checkForm(this);">
	<table align="center">
	   <tr><td align="right">Password</td><td><input type="password" name="PWord1"><font color="red"><b>*</b></font> <em>(6-20 alphanumeric chars)</em></td></tr>
	   <tr><td align="right">Re-enter Password</td><td><input type="password" name="PWord2"><font color="red"><b>*</b></font></td></tr>
	   <input type="hidden" name="AdminID" value="<?php echo $myID;?>" />
	   <input type="hidden" name="act" value="update" />
	   <tr><td align="center" colspan="2"><input type="submit" value="Reset Password!"><em>(<font color="red"><b>*</b> required field</font>)</em></td></tr>
	</table>    
	</form>
	<?
	print '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';
	@mysql_free_result($result); #free resources
}

function updateExecute()
{
	global $config;
	if(isset($_POST['AdminID']) && (int)$_POST['AdminID'] > 0)
	{
	 	$myID = (int)$_POST['AdminID']; #Convert to integer, will equate to zero if fails
	}else{
		feedback("AdminID not numeric");
		myRedirect($config->adminReset);
	}
	
	if(!onlyAlphaNum($_POST['PWord1']))
	{//data must be alphanumeric or punctuation only	
		feedback("Data entered for password must be alphanumeric only");
		myRedirect(THIS_PAGE);
	}
	$myConn = conn('',FALSE); 
	$redirect = $config->adminReset; # global var used for following formReq redirection on failure
	$AdminID = formReq('AdminID');  # calls dbIn internally, to check form data
	$AdminPW = formReq('PWord1');

     # SHA() is the MySQL function that encrypts the password
	$sql = sprintf("UPDATE " . PREFIX . "Admin set AdminPW=SHA('%s') WHERE AdminID=%d",$AdminPW,$AdminID);
 
	@mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
	
	 //feedback success or failure of insert
	 if (mysql_affected_rows($myConn) > 0){$msg= "Password Reset!";}else{$msg="PASSWORD NOT RESET! (or not changed from original value)";}

      //put links on page to reset form, exit
      echo '<div align="center"><h3>' . $msg . '</h3></div>';
      echo '<div align="center"><a href="' . $config->adminReset . '">Reset Another Password</a></div>';
      echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';	
}
?>
