<?php
function findRoot() { return(substr($_SERVER['SCRIPT_FILENAME'], 0, (stripos($_SERVER['SCRIPT_FILENAME'], $_SERVER['SCRIPT_NAME'])+1))); }

include(findRoot() . 'run.php');

if(isset($_POST['submit'])) {
	$name_field = $_POST['name']; //name of sender
	$email_field = $_POST['email']; //email of sender
	$message = $_POST['message']; //message from sender
	$page = $_POST['page']; //page comment is on
	$to = 'me@thanegill.com'; //to address
	$subject = 'Message from ' . $name_field . ' [ITC - $page page]'; //subject of sent message
	$body = 'Name: $name_field\n Email: ' . $email_field . '\n Message:\n\n' . $message; //body of sent message
	
	//successful sending
	$title = 'Thank You!';
	$alert = 'Thank you' .  $name_field . 'Your message has been sent.'; // successful sending alert
	mail($to, $subject, $body);
} else {
	//error
	$title = 'ERROR';
	$alert = 'I\'m sorry, there was an error. Please try again.'; //error alert
}

$displayHead('Thank You');
?>
<!--form handler and thank you message-->
	<article id='thank_you'>
		<h1 class='aligncenter'><?php echo($title); ?></h1>
		<h3 class='aligncenter'><?php echo($alert); ?></h3>
		<div class='aligncenter'><p><a href='/'>Back Home</a></p></div>
	</article>
<?php displayFooter(); ?>