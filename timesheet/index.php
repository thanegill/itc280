<?php

function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }

include(findRoot() . 'run.php');

displayHead('Time Sheet'); //Site Title (address bar) appended with " | Thane Gill"

?>
<article class="center">
    <h1>Time Sheet</h1>
	<table class="inLineTable">
		<thead>
			<tr>
				<th>Assignment</th>
				<th class="width35">Date</th>
				<th class="width10">Time</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$totalTime = 0;
		for ($i = 0; $i < sizeof($articles); $i++) {
			if($articles[$i]->GetType() == 'assignment' || $articles[$i]->GetType() == 'extracredit') {
				echo('<tr>
						<td class="alignleft vmiddle"><a href="/?a=' . $articles[$i]->getLink() . '">' . $articles[$i]->getFullTitle() . '</a></td>
						<td class="alignright vmiddle">' . $articles[$i]->getDatePretty() . '</td>
						<td class="aligncenter vmiddle">' . displayTimeSpent($articles[$i]->getTimeSpent()) . '</td>
					</tr>');
					
				$totalTime += $articles[$i]->getTimeSpent();
			}
		}
	?>
			<tr>
				<td></td>
				<td class="alignright vmiddle"><strong>Total Time:</strong></td>
				<td class="aligncenter vmiddle"><strong><?php echo(displayTimeSpent($totalTime)); ?></strong></td>
			</tr>
		</tbody>
	</table>
</article>

<?php
	displayCommentForm();
	displayFooter();
?>