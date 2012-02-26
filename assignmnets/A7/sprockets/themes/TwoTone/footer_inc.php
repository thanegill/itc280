<?php
/**
 * footer_inc.php provides the right panel and footer for our site pages 
 *
 * Includes dynamic copyright data 
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 1.2 2009/10/30
 * @link http://www.newmanix.com/  
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see template.php
 * @see header_inc.php 
 * @todo none
 */
?>
	  <!-- footer include starts here -->
	  </td>
	  <!-- right panel starts here -->	
	  <!-- change right panel color here -->
      	<td width="175" bgcolor="#FDEEF4" valign="top">
		<? 
			echo $config->sidebar2; #change on per page basis or edit in config_inc.php 
		?>
        </td>
	</tr>
      <!-- change footer color here -->
	<tr bgcolor="#52F3FF">
		<td colspan="3">
		    <p align="center"><b>Footer Goes Here!</b></p>
			<p align="center">Always include some sort of copyright notice, for example:</p>
	        <p align="center"><em><? echo $config->copyright; ?></em></p>
		</td>
  </tr>
</table>
</body>
</html>