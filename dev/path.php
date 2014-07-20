<?php
//path.php
//Testing the path command

$path = getcwd();
echo('Your Absolute Path is: ');
echo $path;

function echobr($str) {
	echo($str . '<br/>');
}

echobr($_SERVER['PHP_SELF']);
//The filename of the currently executing script, relative to the document root. For instance, $_SERVER['PHP_SELF'] in a script at the address http://example.com/test.php/foo.bar would be /test.php/foo.bar. The __FILE__ constant contains the full path and filename of the current (i.e. included) file.

//If PHP is running as a command-line processor this variable contains the script name since PHP 4.3.0. Previously it was not available.

echobr($_SERVER['QUERY_STRING']);
//The query string, if any, via which the page was accessed.

echobr($_SERVER['SCRIPT_FILENAME']);
//The absolute pathname of the currently executing script.

//Note: If a script is executed with the CLI, as a relative path, such as file.php or ../file.php, $_SERVER['SCRIPT_FILENAME'] will contain the relative path specified by the user.

echobr($_SERVER['PATH_TRANSLATED']);
//Filesystem- (not document root-) based path to the current script, after the server has done any virtual-to-real mapping.

//Note: As of PHP 4.3.2, PATH_TRANSLATED is no longer set implicitly under the Apache 2 SAPI in contrast to the situation in Apache 1, where it's set to the same value as the SCRIPT_FILENAME server variable when it's not populated by Apache. This change was made to comply with the CGI specification that PATH_TRANSLATED should only exist if PATH_INFO is defined.

//Apache 2 users may use AcceptPathInfo = On inside httpd.conf to define PATH_INFO.

echobr($_SERVER['SCRIPT_NAME']);
//Contains the current script's path. This is useful for pages which need to point to themselves. The __FILE__ constant contains the full path and filename of the current (i.e. included) file.

echobr($_SERVER['REQUEST_URI']);


header("content-type:text/plain"); 

$keys = array(
    "PATH_INFO",
    "PATH_TRANSLATED",
    "PHP_SELF",
    "REQUEST_URI",
    "SCRIPT_FILENAME",
    "SCRIPT_NAME",
    "QUERY_STRING"
);

$info_row = "<tr><td>$_SERVER[SERVER_SOFTWARE]</td><td></td><td></td>\n";
print "Path Information for $_SERVER[SERVER_SOFTWARE]\n\n";

foreach($keys as $key) {
    print '$_SERVER["'.$key.'"] = '.$_SERVER[$key]."\n";
    $info_row .= "<td>$_SERVER[$key]</td>\n";
}

print '__FILE__ = '. __FILE__;
$info_row .= "<td>".__FILE__."</td>\n</tr>";

print "\n\n\n" . $info_row;

?>