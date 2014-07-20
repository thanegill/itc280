<?php
function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }

include(findRoot() . 'run.php');

displayHead('Contact Me');

?>
<article class="center">
	<h1 class="center">Contact Me</h1>
	<form class="comment_form contact" name="comment_form" method="POST" action="/thankyou" onSubmit="return evalid()">
		<table>
			<tr>
				<td class="text"><p>Name:</p></td>
				<td><input type="text" name="name" size="20" placeholder="Name"></td>
			</tr>
			<tr>
				<td class="text"><p>Email:</p></td>
				<td><input type="text" name="email" size="20" placeholder="you@example.com"></td>
			</tr>
			<tr>
				<td class="text"><p>Message:</p></td>
				<td>
					<textarea name="message" cols="35" rows="10" placeholder="Hello!"></textarea>
				</td>
			</tr>
			<tr>
				<td><input type="hidden" name="page" value="<?php echo($pageTitle); ?>"></td>
				<td><input type="submit" value="submit" name="submit" onkeyup="return limitarelungime(this, 255)"></td>
			</tr>
		</table>
	</form>
</article>
<?php displayFooter(); ?>