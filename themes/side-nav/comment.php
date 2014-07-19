<form class="comment_form" name="comment_form" method="POST" action="/thankyou" onSubmit="return evalid()">
	<h2>Submit a Comment</h2>
	<p><?php echo(COMMENT_INSTUSTIONS); ?></p>
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
			<td class="text"><p>Comment:</p></td>
			<td>
				<textarea name="message" cols="30" rows="8" placeholder="Hello!"></textarea>
			</td>
		</tr>
		<tr>
			<td><input type="hidden" name="page" value="<?php echo(PAGE_TITLE); ?>"></td>
			<td><input type="submit" value="Submit" name="submit" onkeyup="return limitarelungime(this, 255)"></td>
		</tr>
	</table>
</form>