<?php
/**
 * $config->adminLogin.php entry point (form) page to administrative area
 *
 * Works with $config->adminValidate.php to process administrator login requests.
 * Forwards user to admin.php, upon successful login. 
 * 
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/ 
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see $config->adminValidate.php
 * @see admin.php
 * @see $config->adminLogout.php
 * @see admin_only_inc.php     
 * @todo none
 */
 
require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials

startSession(); //wrapper for session_start()
//END CONFIG AREA ----------------------------------------------------------

if(isset($_SESSION['red'])) {
	$red = $_SESSION['red'];
} else {
	$red = '';
}#required for redirect back to previous page
$config->loadhead = '
<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
<script type="text/javascript">
	addOnload(init);
	function init()
	{
		document.getElementById("em").focus();
	}
</script>
'; #add form focus via $loadhead var

get_header(); #defaults to theme header or header_inc.php
?>
<article class="center">
	<h1>Admin Login</h1>
	<form class="contact comment_form" action="<? echo $config->adminValidate;?>" method="post">
		<table  align="center">  
			<tr>
				<td class="text">Email:</td>
				<td><input type="text" size="25" maxlength="60" name="em" id="em" /></td>
			</tr>
			<tr>
				<td class="text">Password:</td>
				<td><input type="password" size="25" maxlength="25" name="pw" id="pw" /></td>
			</tr>
			<input type="hidden" name="red" value="<?php echo $red;?>" /> 
			<tr><td align="center" colspan="2"><input type="submit" value="login"></td></tr>
		</table>
	</form>
</article>
<?php
get_footer(); #defaults to theme footer or footer_inc.php
?>
