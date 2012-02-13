<?php

function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }

include(findRoot() . 'run.php');

displayHead('Time Sheet'); //Site Title (address bar) appended with " | Thane Gill"

?>
<article>
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
		for ($i=(sizeof($posts)-1); 0 <= $i; $i--) {
			if($posts[$i]->GetType() == 'assignment' || $posts[$i]->GetType() == 'extracredit') {
				echo('<tr>
						<td class="alignleft vmiddle"><a href="/posts/?p=' . $posts[$i]->getLink() . '">' . $posts[$i]->getFullTitle() . '</a></td>
						<td class="alignright vmiddle">' . $posts[$i]->getDatePretty() . '</td>
						<td class="aligncenter vmiddle">' . displayTimeSpent($posts[$i]->getTimeSpent()) . '</td>
					</tr>');
					
				$totalTime += $posts[$i]->getTimeSpent();
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