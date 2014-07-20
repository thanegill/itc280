<?php
/**
 * contact.php is a postback application designed to provide a 
 * contact form for users to email our clients. contact.php references 
 * recaptchalib.php as an include file which provides all the web service plumbing 
 * to connect and serve up the CAPTCHA image and verify we have a human entering data.
 *
 * Only the form elements 'Email' and 'Name' are significant. Any other form 
 * elements added, with any name or type (radio, checkbox, select, etc.) will be delivered via  
 * email with user entered data.  Form elements named with underscores like: "How_We_Heard" 
 * will be replaced with spaces to allow for a better formatted email:
 *
 * <code>
 * How We Heard: Internet
 * </code>
 *
 * If checkboxes are used, place "[]" at the end of each checkbox name, or PHP will not deliver 
 * multiple items, only the last item checked:
 *
 * <code>
 * <input type="checkbox" name="Interested_In[]" value="New Website" /> New Website <br />
 * <input type="checkbox" name="Interested_In[]" value="Website Redesign" /> Website Redesign <br />
 * <input type="checkbox" name="Interested_In[]" value="Lollipops" /> Complimentary Lollipops <br />
 * </code>
 *
 * The CAPTCHA is handled by reCAPTCHA requiring an API key for each separate domain. 
 * Get your reCAPTCHA private/public keys from: http://recaptcha.net/api/getkey
 *
 * Place your target email in the $toAddress variable.  Place a default 'noreply' email address 
 * for your domain in the $fromAddress variable.
 *
 * After testing, change the variable $sendEmail to TRUE to send email.
 *
 * Tech Stuff: To retain data entered during an incorrect CAPTCHA, POST data is embedded in JS array via a 
 * PHP function sendPOSTtoJS().  On page load a JS function named loadElements() matches the 
 * embedded JS array to the form elements on the page, and reloads all user data into the
 * form elements. 
 *
 * @package nmCAPTCHA
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 1.21 2010/0/28
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see util.js
 * @see recaptchalib.php   
 * @todo none
 */

//reCAPTCHA keys:
$publickey = "6Ld818wSAAAAAMeQ6KOUtIZJbF4wf6Jbh2W4E0DH";
$privatekey = "notmypassword";
//For each customer/domain, get a key from http://recaptcha.net/api/getkey

//EDIT THE FOLLOWING:
$fromAddress = "noreply@thanegill.com"; 
$toAddress = "me@thanegill.com";
$website = "ITC280";  //place name of website here
$sendEmail = FALSE; //if true, will send an email, otherwise just show data.
//END CONFIG AREA

$resp = ""; //the response from reCAPTCHA
$error = ""; //the error code from reCAPTCHA, if any
$skipFields = "recaptcha_challenge_field,recaptcha_response_field,Email"; //comma separated list of form elements NOT to store.

include('include/recaptchalib.php'); //required reCAPTCHA class code

//include 'include/header_inc.php'; //PLACE REFERENCE TO YOUR HEADER FILE HERE

?>
<script type="text/javascript" src="include/util.js"></script>

<!-- Edit Required Form Elements via JavaScript Here -->
<script type="text/javascript">
	//here we make sure the user has entered valid data	
	function checkForm(thisForm) {
		//check form data for valid info
		if (empty(thisForm.Name, "Please Enter Your Name")) {
			return false;
		}
		if (!isEmail(thisForm.Email, "Please enter a valid Email Address")) {
			return false;
		}
		return true; //if all is passed, submit!
	}
</script>

<!-- CSS class for required field elements. Move to your CSS? (or not) -->
<style type="text/css">
	.required {
		font-style: italic;
		color: #FF0000;
		font-weight: bold;
	}
</style>

<form class="comment_form contact" action="<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return checkForm(this);">
<?php
if (isset($_POST["recaptcha_response_field"])) {
	// Check for reCAPTCHA response
    $resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
	if ($resp->is_valid) {
	//reCAPTCHA agrees data is valid
         handle_POST($skipFields, $sendEmail, $fromAddress, $toAddress, $website);//process form elements, format and send email.
        
         //Here we can enter the data sent into a database in a later version of this file
?>
        <!-- format HTML here to be your 'thank you' message -->
		<div align="center">
			<h1>Your Comments Have Been Received!</h1>
			<p>Thanks for the input!</p>
			<p>We'll respond via email within 48 hours, if you requested information.</p>
			<p><a href="index.php">Exit</a></p>
		</div>
            
<?php
    } else { //reCATPCHA response says data not valid - prepare for feedback
            $error = $resp->error;
            send_POSTtoJS($skipFields); //function for sending POST data to JS array to reload form elements
    }
}

//show form, either for first time, or if data not valid per reCAPTCHA    
if (!isset($_POST["recaptcha_response_field"])|| $error != "") {
//separate code block to deal with returning failed data, or no data sent yet	
?>
	<!-- below change the HTML to accommodate your form elements - only 'Name' & 'Email' are significant -->
	<h1>Contact Me:</h1>
    <table width="450">
    	<tr>
    		<td class="text"><p><span class="required">*</span>Name:</p></td>
    		<td><input type="text" name="Name" size="20" placeholder="Name"></td>
    	</tr>
    	<tr>
    		<td class="text"><p><span class="required">*</span>Email:</p></td>
    		<td><input type="text" name="Email" size="20" placeholder="you@example.com"></td>
    	</tr>
    	<tr>
    		<td class="text"><p>Comment:</p></td>
    		<td><textarea name="Comments" cols="30" rows="8" placeholder="Hello!"></textarea></td>
    	</tr>
    	<tr>
    		<td colspan="2" align="center"><p><span class="required">(*required)</span></p></td>
    	</tr>
    	<tr>
    		<!-- reCAPTCHA icon appears here: -->
    		<td colspan="2" align="center"><?php echo recaptcha_get_html($publickey, $error);?></td>
    	</tr>
    	<tr>
    		<td colspan="2" align="center"><input type="submit" value="submit" /></td>
    	</tr>
    </table>
    </form>
    
<?php
}

//include 'include/footer_inc.php'; //PLACE REFERENCE TO YOUR FOOTER FILE HERE

//below are the PHP functions required to make all the above work - should not need to edit below
//--------------END WEB PAGE AREA ------------------------//

/**
 * handles POST data and formulates email response.
 * 
 * @param string $skipFields comma separated string of POST elements to be skipped
 * @param boolean $sendEmail indicates whether developer wants email sent or not
 * @param string $fromAddress fallback 'noreply' address for domain hosting page
 * @param string $toAddress address to receive email
 * @param string $website name of website where form was filled out    
 * @return none 
 * @uses show_POST()
 * @todo none
 */
 
function handle_POST($skipFields, $sendEmail, $fromAddress, $toAddress, $website) {
	$aSkip = explode(",",$skipFields); //split form elements to skip into array
	$postData = show_POST($aSkip); //loops through and creates select POST data for display/email

	if(is_email($_POST['Email'])) {
		$fromAddress = $_POST['Email'];
	} //Only use Email for return address if valid 
	
    if($sendEmail) {
    //create email
		if(isset($_POST['Name'])) {
			$Name = " message from: " . $_POST['Name'] . ",";
		} else {
			$Name = "";
		} //Name, if used part of subject
		
		$postData = str_replace("<br />","\n\n",$postData) ;//replace <br /> tags with c/r
    	$Subject= $website . $Name . " " . date('F j, Y g:i a');
		$headers = 'From: '. $fromAddress . "\r\n" . 'Reply-To: ' . $fromAddress . "\r\n" . 'X-Mailer: PHP/' . phpversion();
		$txt = $Subject . "\n\n" . "From Website: " . $website . "\n\n";
		$txt .= $postData;                  
		mail($toAddress, $Subject, $txt, $headers); //here we send off our email
	} else { //print data only
    	print('<p>Data printed only. Email <b>not</b> sent!</p>');
    	echo($postData); //Shows select POST data
		echo('<p><a href="' . $_SERVER['PHP_SELF'] . '">Reset Form</a></p>');
	}

} //end handlePOST()

/**
 * formats PHP POST data to text for email, feedback
 * 
 * @param Array $aSkip array of POST elements to be skipped
 * @return string text of all POST elements & data, underscores removed
 * @todo none
 */
function show_POST($aSkip) {
//formats PHP POST data to text for email, feedback
	$myReturn = ""; //init return var
	foreach($_POST as $varName=> $value) {
	//loop POST vars to create JS array on the current page - include email
	 	if(!in_array($varName,$aSkip) || $varName == 'Email') {
	 	//skip passover elements
	 		$strippedVarName = str_replace("_"," ",$varName);//remove underscores
			if(is_array($_POST[$varName])) {
			//checkboxes are arrays, and we need to loop through each checked item to insert
		 	    $myReturn .= $strippedVarName . ": " . sanitize_it(implode(",",$_POST[$varName])) . "<br />";
	 		} else {//not an array, create line
	 			$strippedValue = nl_2br2($value); //turn c/r to <br />
	 			$strippedValue = str_replace("<br />","~!~!~",$strippedValue);//change <br /> to our 'unique' string: "~!~!~"
	 			//sanitize_it() function commented out as it can cause errors - see word doc
	 			//$strippedValue = sanitize_it($strippedValue); //remove hacker bits, etc. 
	 			$strippedValue = str_replace("~!~!~","\n",$strippedValue);//our 'unique string changed to line break
	 			$myReturn .= $strippedVarName . ": " . $strippedValue . "<br />"; //
	 		}
		}
	}
	return $myReturn;
} //end show_POST()

/**
 * sends PHP POST data to a JS array, where it will be picked up and   
 * matched to form elements, then elements will be reloaded via JS loadElement()
 * 
 * @param string $skipFields comma separated string of POST elements to be skipped
 * @return none
 * @todo none
 */
 
function send_POSTtoJS($skipFields) {
//sends PHP POST data to a JS array, where it will be picked up and matched to form elements, then elements will be reloaded
	$aSkip = explode(",",$skipFields); //split form elements to skip into array
	echo('<script type="text/javascript">');
	echo('var POST = new Array();'); //JS Array is named POST
	foreach($_POST as $varName=> $value) {
	//loop POST vars to create JS array on the current page
	 	if(!in_array($varName, $aSkip) || $varName == 'Email') {
	 	//skip passover elements - all except Email!
			if(is_array($_POST[$varName])) {
			//checkboxes are arrays, and we need to loop through each checked item to insert
		 	    echo('POST["' . $varName . '"] = new Array();');
		 		foreach($_POST[$varName] as $key=>$val){
		 		//here we have an array as an element of an array
		 	    	echo('POST["' . $varName . '"][' . $key . ']="' . $val . '";');
		 		}
	 		} else {//not an array, so likely text, radio or select
	 			echo('POST["' . $varName . '"] = "' .  nl_2br2($value) . '";)');
	 			//nl_2br2() changes c/r to <br /> on the fly, helps JS array!
	 		}
		}		
	}
	echo 'addOnload(loadElements);'; //loadElements in util.js will match form objects to POST array
	echo('</scr');
	echo('ipt>');
} //end send_POSTtoJS()

/**
 * Strips tags & extraneous stuff, leaving text, numbers, punctuation.  
 *
 * Not recommended for databases, but since we're only sending email,
 * this is hopefully better than nothing
 *
 * Change in version 1.11 is to use spaces as replacement instead of empty strings
 *
 * @param string $str data as entered by user
 * @return data returned after 'sanitized'
 * @todo none
 */
 
function sanitize_it($str) {
//We would like to trust the user, and aren't using a DB, but we'll limit input to alphanumerics & punctuation
	$str = strip_tags($str); //remove HTML & script tags	
	$str = preg_replace("/[^[:alnum:][:punct:]]/"," ",$str);  //allow alphanumerics & punctuation - convert the rest to single spaces
	return $str;
} //end sanitize_it()

/**
 * Checks for email pattern using PHP regular expression.  
 *
 * Returns true if matches pattern.  Returns false if it doesn't.   
 * It's advised not to trust any user data that fails this test.
 *
 * @param string $str data as entered by user
 * @return boolean returns true if matches pattern.
 * @todo none
 */
 
function is_email($myString) {
  if(preg_match("/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9_\-]+\.[a-zA-Z0-9_\-]+$/",$myString)) {
	  return true;
  } else {
	  return false;
  }
} //end is_email()

/**
 * br2nl() changes '<br />' tags  to '\n' (newline)  
 * Preserves user formatting for preload of <textarea>
 *
 * <code>
 * $myText = br_2nl($myText); // <br /> changed to \n
 * </code>
 *
 * @param string $text Data from DB to be loaded into <textarea>
 * @return string Data stripped of <br /> tag variations, replaced with new line 
 * @todo none 
 */
 
function br_2nl($text) {
	$nl = "\n";   //new line character
    $text = str_replace("<br />",$nl,$text);  //XHTML <br />
    $text = str_replace("<br>",$nl,$text); //HTML <br>
    $text = str_replace("<br/>",$nl,$text); //bad break!
    return $text;
    /* reference (unsused)
    $cr = chr(13); // 0x0D [\r] (carriage return)
	$lf = chr(10); // 0x0A [\n] (line feed)
	$crlf = $cr . $lf; // [\r\n] carriage return/line feed)
    */
} //end br2nl()

/**
 * nl2br2() changes '\n' (newline)  to '<br />' tags
 * Break tags can be stored in DB and used on page to replicate user formatting
 * Use on input/update into DB from forms
 *
 * <code>
 * $myText = nl_2br2($myText); // \n changed to <br />
 * </code>
 * 
 * @param string $text Data from DB to be loaded into <textarea>
 * @return string Data stripped of <br /> tag variations, replaced with new line 
 * @todo none
 */
 
function nl_2br2($text) {
	$text = str_replace(array("\r\n", "\r", "\n"), "<br />", $text);
	return $text;
} //end nl2br2()

?>