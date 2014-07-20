<?php
function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }

include(findRoot() . 'run.php');

displayHead('Template');
?>
<article>
	 <!-- Article Goes Here -->
</article>
<?php
displayCommentForm();
displayFooter();
?>