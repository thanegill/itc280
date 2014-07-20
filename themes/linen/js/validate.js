function evalid(){
	//Empty field validation
	var name=document.comment_form.name;
	if ((name.value===null)||(name.value==="")){
		alert("Please enter your name");
		name.focus();
		return false;
	}
	var email=document.comment_form.email;
	if (checkEmail(email.value)===false){
		alert("Please enter a valid email address");
		email.focus();
		return false;
	}
	var message=document.comment_form.message;
	if ((message.value===null)||(message.value==="")){
		alert("Please enter a message");
		message.focus();
		return false;
	}
	return true;
}
function checkEmail(str) {
	//Email validation
	var at="@";
	var punct=".";
	var lat=str.indexOf(at);
	var lstr=str.length;
	var lpunct=str.indexOf(punct);
	if (str.indexOf(at)==-1){
		return false;
	}
	if (str.indexOf(at)==-1 || str.indexOf(at)===0 || str.indexOf(at)==lstr){
		return false;
	}
	if (str.indexOf(punct)==-1 || str.indexOf(punct)===0 || str.indexOf(punct)==lstr){
		return false;
	}
	if (str.indexOf(at,(lat+1))!=-1){
		return false;
	}
	if (str.substring(lat-1,lat)==punct || str.substring(lat+1,lat+2)==punct){
		return false;
	}
	if (str.indexOf(punct,(lat+2))==-1){
		return false;
	}
	if (str.indexOf(" ")!=-1){
		return false;
	}
	return true;
}