<?php
/**
 * admin_info.php shows phpInfo() command results
 *
 * @package nmAdmin
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.102 2012/03/09
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @todo none
 */
 
require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials

$access = "admin"; #admins can edit themselves, developers can edit any - don't change this var or no one can edit their own data
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var
# END CONFIG AREA ---------------------------------------------------------- 
phpInfo();
?>
