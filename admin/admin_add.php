<?php
/**
 * $config->adminAdd.php is a single page web application that adds an administrator 
 * to the admin database table
 *
 * This page is public by default, but as soon as you add yourself to the database, 
 * make the page private by removing the commented referenct to admin_only_inc.php on 
 * approximately line 54 of this page
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see admin_only_inc.php
 * @todo Currently the JS file is hard wired to a folder named 'include' inside 
 * $config->adminAdd.php & admin_reset.php.  Please change this path in these files until this is fixed.
 */

require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials

//END CONFIG AREA ----------------------------------------------------------

$access = "superadmin"; #superadmin or above can add new administrators
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var

get_header(); #defaults to theme header or header_inc.php
 
if (isset($_POST['Email']))
{# if Email is set, check for valid data
	if(!onlyEmail($_POST['Email']))
	{//data must be alphanumeric or punctuation only	
		feedback("Data entered for email is not valid");
		myRedirect($config->adminAdd);
	}
		
	if(!onlyAlphaNum($_POST['PWord1']))
	{//data must be alphanumeric or punctuation only	
		feedback("Password must contain letters and numbers only.");
		myRedirect($config->adminAdd);
	}	

	$myConn = conn('',FALSE); # MUST precede formReq() function, which uses active connection to parse data
	$redirect = THIS_PAGE; # global var used for following formReq redirection on failure
	$FirstName = formReq('FirstName');  # formReq calls dbIn() internally, to check form data
	$LastName = formReq('LastName');
	$AdminPW = formReq('PWord1');
	$Email = strtolower(formReq('Email'));
	$Privilege = formReq('Privilege');

	#sprintf() function allows us to filter data by type while inserting DB values.  Illegal data is neutralized, ie: numerics become zero
	$sql = sprintf("INSERT into " . PREFIX . "Admin (FirstName,LastName,AdminPW,Email,Privilege,DateAdded) VALUES ('%s','%s',SHA('%s'),'%s','%s',NOW())",
            $FirstName,$LastName,$AdminPW,$Email,$Privilege);

	@mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));  # insert is done here
	
	 # feedback success or failure of insert
	 if (mysql_affected_rows($myConn) > 0){$msg= "Administrator Added!";}else{$msg="RECORD NOT INSERTED!";}
      # Success? Place links on page to reset form, add another, exit
      echo '<div align="center"><h3>' . $msg . '</h3></div>';
      echo '<div align="center"><a href="' . $config->adminAdd . '">Add Administrator</a></div>';
      echo '<div align="center"><a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';

}else{ //show form - provide feedback
?>
	<!-- JavaScript include file holds all form validation functions -->
	<script type="text/javascript" src="<?=VIRTUAL_PATH;?>include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm) {
				//check form data for valid info
				if(empty(thisForm.FirstName,"Please Enter Administrator's First Name")){return false;}
				if(empty(thisForm.LastName,"Please Enter Administrator's Last Name")){return false;}
				
				if(!isEmail(thisForm.Email,"Please enter a valid Email Address")){return false;}
				if(!isAlphanumeric(thisForm.PWord1,"Only alphanumeric characters are allowed for passwords.")){thisForm.PWord2.value="";return false;}
				if(!correctLength(thisForm.PWord1,6,20,"Password does not meet the following requirements:")){thisForm.PWord2.value="";return false;}
				if(thisForm.PWord1.value != thisForm.PWord2.value) {
					//match password fields
		   			alert("Password fields do not match.");
		   			thisForm.PWord1.value = "";
		   			thisForm.PWord2.value = "";
		   			thisForm.PWord1.focus();
		   			return false;
	   			}
				return true;//if all is passed, submit!
			}
	</script>
	<article class="center">
	<h1>Add New Administrator</h1>
		<p align="center">Be sure to write down password!!</p>
		<form class="comment_form contact" action="<?=$config->adminAdd;?>" method="post" onsubmit="return checkForm(this);">
			<table>
				<tr>
					<td align="center" colspan="2"><small><font color="red">(<b>*</b> required field)</font></small></td>
				</tr>
				<tr>
				   <td class="text">First Name</td>
				   <td><input type="text" name="FirstName"><font color="red"><b>*</b></font></td>
				</tr>
				<tr>
				   <td class="text">Last Name</td>
				   <td><input type="text" name="LastName"><font color="red"><b>*</b></font></td>
				</tr>
				<tr>
				   <td class="text">Email</td>
				   <td><input type="text" name="Email"><font color="red"><b>*</b></font></td>
				</tr>
				<tr>
					<td class="text">Privilege:</td>
					<td>
					<?php
						$privileges = getENUM(PREFIX . 'Admin','Privilege'); #grab all possible 'Privileges' from ENUM
						#createSelect(element-type,element-name,values-array,db-array,labels-array,concatentator) - creates preloaded radio, select, checkbox set
						createSelect("select","Privilege",$privileges,"",$privileges,",");
					?>
					</td>
				</tr>
				<tr>
					<td class="text">Password</td>
					<td><input type="password" name="PWord1"><font color="red"><b>*</b></font></td>
				</tr>
				<tr>
					<td class="text">Re-enter Password</td>
					<td><input type="password" name="PWord2"><font color="red"><b>*</b></font></td>
				</tr>
				<tr>
					<td align="center" colspan="2"><input type="submit" value="Add-Min!"></td>
				</tr>
			</table>    
		</form>
		<div align="center"><a href="<?=$config->adminDashboard;?>">Exit To Admin Page</a></div>
	</article>
<?php
}
get_footer(); #defaults to theme footer or footer_inc.php
?>
