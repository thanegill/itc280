<?php
/**
 * admin.php session protected 'dashboard' page of links to administrator tool pages
 *
 * Use this file as a landing page after successfully logging in as an administrator.  
 * Be sure this page is not publicly accessible by referencing admin_only_inc.php
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see $config->adminLogin.php
 * @see $config->adminValidate.php
 * @see $config->adminLogout.php
 * @see admin_only_inc.php 
 * @todo none
 */

require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials

//END CONFIG AREA ----------------------------------------------------------
$access = "admin"; #admin or higher level can view this pge
include_once(INCLUDE_PATH . 'admin_only_inc.php'); #session protected page - level is defined in $access var

$feedback = ""; #initialize feedback
if(isset($_GET['msg'])) {
	switch($_GET['msg']) {
	    case 1:
	       $feedback = "Your administrative permissions don't allow access to that page.";
	       break;
		default:
	       $feedback = "";
	}
}

if($feedback != ""){$feedback = '<div align="center"><h4><font color="red">' . $feedback . '</font></h4></div>';} #Fill out feedback HTML

get_header(); #defaults to theme header or header_inc.php

?>
<article class="center">
	<h1>Site Admin Page</h1>
	<?php echo $feedback; #feedback, if any, provided here ?>
	<table class="inLineTable" align="center" width="98%" cellpadding="3" cellspacing="3">
		<thead>
			<tr>
				<th>Page</th>
				<th>Purpose</th>
			</tr>
		</thead>
		<tbody>
		<?php if($_SESSION['Privilege']=="developer"){ ?>
		<tr>
			<td width="250" align="center"><a href="/adminer.php">Adminer</a></td>
			<td>
				<b>Developer Only.</b> Adminer is a low overhead MySQL administrative tool.  Use it to create, backup and alter 
				MySQL database tables. (Requires MySQL credentials for login)</p>
			</td>
		</tr>
		<tr>
			<td width="250" align="center"><a href="<?php echo $config->tableEditor; ?>">Table Editor</a></td>
			<td>
				<p><b>Developer Only.</b> The table editor is for quick editing of info in MySQL database tables.</p>
				<p>This file is added in the <b>nmEdit</b> package</p>
			
			
			</td>
		</tr>
			<tr>
			<td width="250" align="center"><a href="<?=ADMIN_PATH;?>admin_error_list.php">View Error Log Files</a></td>
			<td><b>Developer Only.</b> View & Delete error log files</td>
		</tr>
		</tr>
			<tr>
			<td width="250" align="center"><a href="<?=ADMIN_PATH;?>admin_info.php" target="_blank">View php_Info()</a></td>
			<td><b>Developer Only.</b> View phpInfo() command for file pathing, environment info.</td>
		</tr>		
				
		<?php
		}
		if($_SESSION['Privilege']=="superadmin" || $_SESSION['Privilege']=="developer"){ ?>
		<tr>
			<td width="250" align="center"><a href="<?php echo $config->adminAdd; ?>">Add Administrator</a></td>
			<td><b>SuperAdmin Only.</b> Create site administrators, of any level.</td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td width="250" align="center"><a href="<?php echo $config->adminReset; ?>">Reset Administrator Password</a></td>
			<td>Reset Admin passwords here.  SuperAdmins can reset the passwords of others.</td>
		</tr>
		<tr>
			<td width="250" align="center"><a href="<?php echo $config->adminEdit; ?>">Edit Administrator Data</a></td>
			<td>Edit Admin data such as first, last & email here.  SuperAdmins can edit the Privilege levels of others.</td>
		</tr>
		<tr>
			<td width="250" align="center">
				<p><a href="<?=VIRTUAL_PATH;?>rte_test.php">Edit this page</a></p>
				<p><a href="<?=VIRTUAL_PATH;?>rte_test.php">Edit that page</a></p>
				<p><a href="<?=VIRTUAL_PATH;?>rte_test.php">Edit Edit Edit</a></p>
			</td>
			<td><p>View site pages as an admin.  Various pages can be setup for special editing.  For example, 'view' pages can have image upload enabled.  Later we can install Rich Text Editors (RTEs) to allow inline editing of pages by administrators. You could place a link to each editable page here as well.</p>
			<p>You'll want to place links to special site pages here so admins know where to go to edit page data or upload images, etc.</p>
			<p>The link shown, to rte_test.php is added with the <b>RTE</b> package</p>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2"><a href="<?php echo $config->adminLogout; ?>" title="Don't forget to Logout!">Logout</a></td>
		</tr>
		</tbody>
	</table>
</article>
<?php 

get_footer();

?>
