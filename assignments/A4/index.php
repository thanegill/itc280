<?php
function findRoot() { return(substr($_SERVER["SCRIPT_FILENAME"], 0, (stripos($_SERVER["SCRIPT_FILENAME"], $_SERVER["SCRIPT_NAME"])+1))); }

include(findRoot() . 'run.php');

displayHead('Assignmnet 4 CAPTCHA');

?>
<article>

<?php include('contact.php'); ?>

</article>

<?php displayFooter(); ?>