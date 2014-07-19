<?php
/**
 * $config->adminEdit.php is a single page web application that allows an admin to 
 * edit some of their personal data
 *
 * This page is an addition to the application started as the nmAdmin package
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/  
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see $config->adminAdd.php 
 * @see admin_reset.php
 * @see admin_only_inc.php
 * @todo Add ability to change privilege level of admin by developer - add ability of SuperAdmin to change priv. level
 */

require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials

//END CONFIG AREA ----------------------------------------------------------

$access = "admin"; #admins can edit themselves, developers can edit any - don't change this var or no one can edit their own data
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

get_header(); #defaults to theme header or header_inc.php

switch ($myAction) 
{//check for type of process
	case "edit": # 2) show form to edit data
	 	editDisplay();
	 	break;
	case "update": # 3) execute SQL, redirect
		updateExecute();
		break; 
	default: # 1)Select Administrator
	 	selectAdmin();
}
//foreach ($_GET as $varName){unset($varName);}
//foreach ($_POST as $varName){unset($varName);}

get_footer(); #defaults to theme footer or footer_inc.php


function selectAdmin() {
	//Select administrator
	global $config;
	echo '<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm)
			{//check form data for valid info
				//if(!checkRadio(thisForm.AdminID,"Please Select an Administrator.")){return false;}
				if(empty(thisForm.AdminID,"Please Select an Administrator.")){return false;}
				return true;//if all is passed, submit!
			}
	</script>
	<article>
	<h1 align="center">Edit Administrator Data</h1>';
	if($_SESSION["Privilege"] == "developer" || $_SESSION["Privilege"] == "superadmin") {
		# must be greater than admin level to have  choice of selection
		echo '<p align="center">Select an Administrator, to edit their data:</p>';
	}
	echo '<form class="comment_form contact" action="' . $config->adminEdit . '" method="post" onsubmit="return checkForm(this);">';
	$myConn = conn("", FALSE);
	$sql = "select AdminID,FirstName,LastName,Email,Privilege,LastLogin,NumLogins from " . PREFIX . "Admin";
	if($_SESSION["Privilege"] != "developer" && $_SESSION["Privilege"] != "superadmin") {
		# limit access to the individual, if not developer level
		$sql .= " where AdminID=" . $_SESSION["AdminID"];
	}
	$result = mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
	if (mysql_num_rows($result) > 0)//at least one record!
	{//show results
		echo '<form lass="comment_form contact" action="' . $config->adminEdit . '" method="post" onsubmit="return checkForm(this);">';
		echo '<table border="1" style="border-collapse:collapse">';
		echo '<tr><th>AdminID</th><th>Admin</th><th>Email</th><th>Privilege</th></tr>';
		while ($row = mysql_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     echo '<tr><td align="center">';
		     echo '<input type="radio" name="AdminID" value="' . (int)$row['AdminID'] . '">&nbsp;';
		     echo (int)$row['AdminID'] . '</td>';
		     echo '<td>' . dbOut($row['FirstName']) . ' ' . dbOut($row['LastName']) . '</td>';
		     echo '<td>' . dbOut($row['Email']) . '</td>';
		     echo '<td>' . dbOut($row['Privilege']) . '</td></tr>';
		}
		echo '<input type="hidden" name="act" value="edit" />';
		echo '<tr><td align="center" colspan="4"><input type="submit" value="Choose Admin!"></em></td></tr>';
		echo '</table></form>';	
	}else{//no records
      echo '<div align="center"><h3>Currently No Administrators in Database.</h3></div>';
	}
	 echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';
	@mysql_free_result($result); //free resources

}

function editDisplay() {
	global $config;
	
	if(isset($_POST['AdminID']) && (int)$_POST['AdminID'] > 0) {
	 	$myID = (int)$_POST['AdminID']; #Convert to integer, will equate to zero if fails
	} else {
		feedback("AdminID not numeric");
		myRedirect($config->adminEdit);
	}
	$privileges = getENUM(PREFIX . 'Admin','Privilege'); #grab all possible 'Privileges' from ENUM

	$myConn = conn('',FALSE);
	$sql = sprintf("select FirstName,LastName,Email,Privilege from " . PREFIX . "Admin WHERE AdminID=%d",$myID);
	$result = @mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
	if(mysql_num_rows($result) > 0)//at least one record!
	{//show results
		while ($row = mysql_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     $FirstName = dbOut($row['FirstName']);
		     $LastName = dbOut($row['LastName']);
		     $Email = dbOut($row['Email']);
		     $Privilege = dbOut($row['Privilege']);
		}
	} else {//no records
      //put links on page to reset form, exit
      echo '<div align="center"><h3>No such administrator.</h3></div>';
      echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';
	}
	?>
	<script type="text/javascript" src="<?php echo VIRTUAL_PATH; ?>include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm) {
			//check form data for valid info
			if(empty(thisForm.FirstName,"Please enter first name.")){return false;}
			if(empty(thisForm.LastName,"Please enter last name.")){return false;}
			if(!isEmail(thisForm.Email,"Please enter a valid Email Address")){return false;}
			return true;//if all is passed, submit!
		}
	</script>
	
	<article  class="center">
		<h1>Edit Administrator</h1>
		<form class="comment_form contact" action="<?=$config->adminEdit;?>" method="post" onsubmit="return checkForm(this);">
			<table>
				<tr>
					<td align="right">First Name</td>
					<td><input type="text" name="FirstName" value="<?=$FirstName;?>" /><font color="red"><b>*</b></font></td>
				</tr>
					<tr><td align="right">Last Name</td>
					<td><input type="text" name="LastName" value="<?=$LastName;?>" /><font color="red"><b>*</b></font></td>
				</tr>
					<tr><td align="right">Email</td>
					<td><input type="text" name="Email" value="<?=$Email;?>" /><font color="red"><b>*</b></font></td>
				</tr>
				<?php
					if($_SESSION["Privilege"] == "developer" || $_SESSION["Privilege"] == "superadmin"){
						# superadmin or developer may change the privileges of others
						# uses createSelect() function to preload the select option
						echo '<tr><td>Privilege</td><td>';
						# createSelect(element-type,element-name,values-array,db-array,labels-array,concatentator) - creates preloaded radio, select, checkbox set
						createSelect("select","Privilege",$privileges,$Privilege,$privileges,",");	#privileges is from ENUM	
						echo '</td></tr>';
					} else {
						echo '<input type="hidden" name="Privilege" value="' . $_SESSION["Privilege"] . '" />';
					}
				?>
				<input type="hidden" name="AdminID" value="<?=$myID;?>" />
				<input type="hidden" name="act" value="update" />
				<tr><td align="center" colspan="2"><input type="submit" value="Update Admin"><em>(<font color="red"><b>*</b> required field</font>)</em></td></tr>
			</table>   
		</form>
	<article>
	<?
	echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';
	@mysql_free_result($result); //free resources
}

function updateExecute()
{
	global $config;
	$myConn = conn('',FALSE); # MUST precede formReq() function, which uses active connection to parse data
	$redirect = $config->adminEdit; # global var used for following formReq redirection on failure
	$FirstName = formReq('FirstName');  # formReq calls dbIn() internally, to check form data
	$LastName = formReq('LastName');
	$Email = strtolower(formReq('Email'));
	$Privilege = formReq('Privilege');
	$AdminID = formReq('AdminID');
	
	#check for duplicate email
	$sql = sprintf("select AdminID from " . PREFIX . "Admin WHERE (Email='%s') and AdminID != %d",$Email,$AdminID);
	$result = mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
	if(mysql_num_rows($result) > 0)//at least one record!
	{# someone already has email!
		feedback("Email already exists - please choose a different email.");
		myRedirect($config->adminEdit); # duplicate email
	}

	#sprintf() function allows us to filter data by type while inserting DB values.  Illegal data is neutralized, ie: numerics become zero
	$sql = sprintf("UPDATE " . PREFIX . "Admin set FirstName='%s',LastName='%s',Email='%s',Privilege='%s' WHERE AdminID=%d",$FirstName,$LastName,$Email,$Privilege,(int)$AdminID);
 	
	mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
	
	 //feedback success or failure of insert
	 if (mysql_affected_rows($myConn) > 0){
		 $msg= "Admin Updated!";
		 startSession(); #update session data
		 if($_SESSION["AdminID"] == $AdminID)
		 {#this is me!  update current session info:
			$_SESSION["Privilege"] = $Privilege;
		 	$_SESSION["FirstName"] = $FirstName;
		 }
	 }else{
		 $msg="ADMIN NOT UPDATED!";
	}

      //put links on page to reset form, exit
      echo '<div align="center"><h3>' . $msg . '</h3></div>';
      echo '<div align="center"><a href="' . $config->adminEdit . '">Edit Another Admin</a></div>';
      echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';	
}

?>
