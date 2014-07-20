<?php
/**
 * nmEdit.php is PHP scaffolding for editing data in MySQL databases as we develop
 * web applications.
 *
 * Used by permission. Please keep versioning and authorship info intact, so others may find updates
 * @package nmEdit
 * @author Bill Newman williamnewman@gmail.com
 * @version 1.53 2012/03/11
 * @copyright Copyright (c) 2002-2010, Bill & Sara Newman
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @todo Add JS data entry protection.
 * @todo Work ongoing for 'command' support
 * @todo Change numerical global vars to boolean constants
 * @todo Work on datetime in createField()
 * @todo Add phpDocumentor documentation for functions
 * @todo Create single file version
 * @todo Create parameterized version, with an INC file, and multiple configuration/address files - pass thru editor
 * @todo Add multi-join table dropdown support
 * @todo Add constant based VIRTUAL_PATH to assets  
 */
 
require '../includes/config_inc.php'; #provides configuration, pathing, error handling, db credentials  
 
$myDB = "thanegill_itc280"; #identify current database
$defaultTable = "test_Customers";  #identify which table you wish to show by default
$allowEdit = 0; #1 allows add/edit/delete capabilities, zero disallows and hides
$allowDelete = 0; #1 allows deletes (no effect on update) zero disallows and hides
$allowQuery = 0; #1 allows SQL queries but no commands.  Zero disallows and hides
$allowCommand = 0; #1 allows raw SQL commands (update, alter) zero disallows
$useSandwich = FALSE; #If true will attempt to load sandwich_header_inc.php & sandwich_footer_inc.php from current theme
#$config->theme = "DarkRitual"; #Want to override the current theme on a single page basis?  Uncomment this!

$defaultOrder = "desc"; #default sort order - show me latest entered on top!
$rowsPerPage = 50; #Number of records to appear per page for paging

/**
 * Comma separated list of tables to HIDE (will not show at all)
 *
 * Good for not confusing administrators, or when you have an app built for editing
 * a particular table.
 *
 *<code>
 *$hideTables = "Admin,MySecretTable,MyOtherSecretTable"; 
 *</code>
 */ 
$hideTables = "";

/**
 * Comma separated list of table prefixes to SHOW (rest will not show)
 *
 * Good for not confusing administrators, so they can view only one set of 
 * tables with this editor
 *
 *<code>
 *$showTablePrefix = "lyr_,srv_"; 
 *</code>
 */ 
$showTablePrefix = "";

//$firstButton = VIRTUAL_PATH . "images/gray_first.gif"; #images used for paging, sorting
//$lastButton  = VIRTUAL_PATH . "images/gray_last.gif";
//$nextButton  = VIRTUAL_PATH . "images/gray_next.gif";
//$prevButton  = VIRTUAL_PATH . "images/gray_prev.gif";
//$upButton  = VIRTUAL_PATH . "images/asc.gif";
//$downButton  = VIRTUAL_PATH . "images/desc.gif";

// End config area ----------------------------------------------------------
require_once INCLUDE_PATH . 'admin_only_inc.php'; #admins only can see this page

$myPage = THIS_PAGE;
$myConn = conn('',FALSE);//requires single active connection for each view of page

#check for sandwich_header for theme here...
get_header();

?>
<!-- based on nmEdit.php, version 1.53 by Bill Newman, updated 03/11/2012 http://www.newmanix.com/-->
<script language="JavaScript">
    function jumpTo(list) {
        var URL = "<?=$myPage;?>?tbl=" + list.options[list.selectedIndex].value;
        window.location.href = URL;
    }
    
    function confirmDelete() {
        var agree=confirm("Are you sure you wish to delete these records?");
        if(agree){return true;}else{return false;}
    }
</script>
<?php

if(isset($_REQUEST['tbl'])){$myTable = (trim($_REQUEST['tbl']));}else{$myTable = "";}
if(isset($_REQUEST['msg'])){$myTable = (trim($_REQUEST['msg']));}else{$msg = "";}
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}
if(isset($_GET['sql'])) {
	//override post SQL due qstring paging
    $mySQL = (trim(urldecode($_GET['sql'])));
    $myAction = "selSQL";
} else {
    $mySQL = "";
}
if(isset($_GET['id'])){$myID = (trim($_GET['id']));}else{$myID=0;}
if($myTable == "") {
	#check for bad table here...
	$sql = "SELECT COUNT(*) as foundTable 
	FROM information_schema.tables 
	WHERE table_schema = '" . $myDB . "' 
	AND table_name = '" . $defaultTable . "'";
	
	$result  = @mysql_query($sql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
    list($foundTable) = @mysql_fetch_array($result) or die(trigger_error(mysql_error(), E_USER_ERROR));
	if((int)$foundTable < 1) {
		//get some other table!!
		echo "bad table!!";
		die;
	} else {
		$myTable = $defaultTable; #found it, use it!
	
	}
}
if($myTable != "") {
	//can't grab primary from empty table
    if(isset($_GET['idf'])){$myIDField = (trim($_GET['idf']));}else{$myIDField = grabPrimary($myTable);} #grab primary key here
}
echo '<article>';
switch ($myAction) {//check for type of process
    case "ed": //show edit table
        editDisplay($myID,$myIDField);
        break;
    case "edex":
  		editExecute();
        break;
    case "addex":
        addExecute();
        break;
    case "delex":
        deleteExecute();
        break;
    case "add":
        addDisplay($myIDField);
        break;
    case "selSQL":
        if($mySQL != "")
        {//paging
            tableDisplay($mySQL,$myTable,$msg);
        }else{//new SQL via form
            tableDisplay($_POST['selSQL'],$myTable,$msg);
        }
        break;
    default:
		tableDisplay("",$myTable,$msg);
}
echo '</article>';
foreach ($_GET as $varName){unset($varName);}
foreach ($_POST as $varName){unset($varName);}


#check for sandwich footer for theme here!
get_footer();

//TABLE DISPLAY***********************************
function tableDisplay($selSQL,$myTable,$msg) {
   global $myConn,$allowCommand,$rowsPerPage,$myTable,$allowDelete,$allowEdit,$allowQuery,$myPage,$hideTables,$defaultOrder,$myIDField;
   global $firstButton,$lastButton,$nextButton,$prevButton,$upButton,$downButton,$showTablePrefix,$config;
 
   if($firstButton != ""){$firstButton = '<img src="' . $firstButton . '" border="none" alt="first" title="First Page">';}else{$firstButton = "<b><<</b>";}
   if($lastButton != ""){$lastButton = '<img src="' . $lastButton . '" border="none" alt="first" title="Last Page">';}else{$lastButton = "<b>>></b>";}
   if($nextButton != ""){$nextButton = '<img src="' . $nextButton . '" border="none" alt="first" title="Next Page">';}else{$nextButton = "<b>></b>";}
   if($prevButton != ""){$prevButton = '<img src="' . $prevButton . '" border="none" alt="first" title="Previous Page">';}else{$prevButton = "<b><</b>";}

   if(isset($_REQUEST['od'])){$myDirection = (trim($_REQUEST['od']));}else{$myDirection = "";}
   if(isset($_REQUEST['of'])){$myOrderField = (trim($_REQUEST['of']));}else{$myOrderField = "";}
   $pageNum = 1;//default to first page
   if(isset($_GET['page'])){$pageNum = $_GET['page'];}//get current page
   $offset = ($pageNum - 1) * $rowsPerPage;//get page offset
   $myOffset = " LIMIT $offset, $rowsPerPage";
   $badSQL = "no";  //may change below!!
   $cmd = ''; //possible SQL command
   if($selSQL!="") {
        $selSQL = stripslashes($selSQL);
        $selectCheck = substr($selSQL, 0, 6); //needs to start with select
        $selectCheck = strtolower($selectCheck);
        $myClear = '<a href="' . $myPage . '">Clear SQL</a> &nbsp;';
        $allowEdit = 0; $allowDelete = 0; //override 
        if($selectCheck!="select") //update, etc!
        {
            if($allowCommand == 1)
            {//sql command allowed, process!!
                //allow multiple commands separated by ";"
                $aCommands = explode(";",$selSQL);
                for($x=0;$x<count($aCommands);$x++)
                {
                    $currCommand = trim($aCommands[$x]);
                    if(strlen($currCommand)>5)
                    {
						@mysql_query($currCommand,$myConn)  or die(trigger_error("SQL: " . $currCommand . "<br /> Error: " . mysql_error(), E_USER_ERROR));
                    }
                }
                $cmd = $selSQL;//show command just processed, as well
                $selSQL = ''; //default below
                print '<div align="center"><h4>The Following SQL Statement was processed:</h4>';
                print '<h4><font color="red">' . $cmd . '</font></h4>'; 
            }else{
                print '<div align="center"><h4>The Following SQL Statement was disallowed:</h4>';
                print '<h4><font color="red">' . $cmd . '</font></h4>'; 
                $badSQL = "yes";
            }
        }else{  //valid data
            print '<div align="center"><h4>Result of SQL Statement:</h4>';
            print '<h4><font color="red">' . $selSQL . '</font></h4>';
        }
        print $myClear . '<br /></div>';
   }else{
     	if($myTable != "")
        {
            if($defaultOrder != ''){$defaultOrder = " order by " . $myIDField . " " . $defaultOrder;}
            if($myOrderField != ''){$defaultOrder = " order by " . $myOrderField . " " . $myDirection;}
            $selSQL = "select * from " . $myTable . $defaultOrder;
            print '<div align="center"><h4>Table: ' . $myTable . '</h4></div>';
            if($msg!=""){print '<div align="center"><h6>' . $msg . '</h6></div>';}
            $myClear = "";
        }
        
   }
   if($badSQL == "yes"){die(print "</body></html>");}  //protect from disallowed SQL statement
   buildTableSelect($hideTables,$showTablePrefix);  //build drop down table selector

   $strAdd = "";
   $foundRecord = 0;
   if($myTable != "" || $selSQL != "")
    {//if no table chosen, don't show data
        $testsql = strtolower($selSQL); //make lowercase to test for ' from '. Use original SQL to keep case
        $findFrom = strrpos($testsql," from "); //find ' from ' in select statement
        $myFrom = substr($selSQL,$findFrom + 1);  //eliminate select fields so we can re-create count sql
        $rowsql   = "SELECT COUNT(*) AS numrows " . $myFrom;//rows in db

        $result  = @mysql_query($rowsql,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
        $row     = @mysql_fetch_array($result, MYSQL_ASSOC) or die(trigger_error(mysql_error(), E_USER_ERROR));
        $numrows = $row['numrows'];
        $result = @mysql_query(($selSQL . $myOffset),$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
        $numColumns = mysql_num_fields($result);
        if($numrows > $rowsPerPage)
        {
            print '<div align="center">';
            $qstr = '&tbl=' . $myTable;
            if($selSQL!=""){$qstr .= "&sql=" . urlencode($selSQL);}//add sql statement to qstring
            showPaging($numrows,$rowsPerPage,$pageNum,$firstButton,$prevButton,$nextButton,$lastButton,false,true,$qstr);
            print '</div>';
         }
        if ($allowDelete==1){print '<form name="deleteForm" action="' . $myPage . '" method="post" onSubmit="return confirmDelete()">';}
        
		print '<div align="center">';
		print '<table class="edTBL" ><tr class="headerRow">';
        if ($allowDelete==1){print '<input type=hidden name="act" value="delex">';}
        if ($allowDelete==1){print '<input type="hidden" name="tbl" value="' . $myTable . '" />';}
        $rowClass = "oddRow";
        for($x = 0; $x < $numColumns; $x++)
        {//process field names
           $fieldName = mysql_field_name($result,$x);
           $dirImg = "";
           if($myOrderField != '')
           {//process order link
              if($myOrderField == $fieldName)
              {//matches!! show arrow!!
                 $dirImg = getDir($myDirection,$upButton,$downButton);
              }
           }
           $qs = buildQS($myDirection,$fieldName,$myOrderField);
           print '<td><a href="' .    $myPage . $qs . '">' . $dirImg . $fieldName . '</a></td>';
        }
        print '</tr>';
        while($row = mysql_fetch_row($result))
        {  //pull data from db array
             $foundRecord=1;  //bool
             print '<tr class="' . $rowClass . '">';
             for($x=0;$x<$numColumns;$x++)
             {
                 $myValue = strip_tags(stripslashes($row[$x]));
                 if(($x==0)&&($allowEdit==1)) //only if edit allowed
                 {//first field includes edit code, rest are static
                    print '<td><a href="' . $myPage . '?act=ed&id=' . $myValue . '&idf=' . $myIDField . '&tbl=' . $myTable . '"><b>' . $myValue . '</b> (Edit)</a>';
                    if ($allowDelete==1){print '<input type="checkbox" name="Delete[]" value="' . $myValue . '"><font color="red"><b>Delete</b></font>';}
                    print '<input type="hidden" name="idf" value="' . $myIDField . '">';
                    print '<input type="hidden" name="id" value="' . $myValue . '">';
                    if($allowEdit==1)
                    {
                        $strAdd = '<a href="' . $myPage . '?act=add&id=' . $myValue . '&idf=' . $myIDField . '&tbl=' . $myTable . '">Add New Record</a>';
                    }else{//no add allowed
                        $strAdd = "";
                    }
                    print '</td>';
                 }else{
                    if(strLen($myValue) > 30)
                    {
                        $tmp = substr($myValue,0,28) . "...";
                        print "<td>" . $tmp . "</td>";
        
                    }else{
                        print "<td>" . $myValue . "</td>";
                    }
                 }
             }
             print "</tr>";
             if($rowClass=="oddRow"){$rowClass="evenRow";}else{$rowClass="oddRow";}//alternate class/colors
        }
         $strNew = '<a href="' . $myPage . '?act=add&id=1&idf=' . $myIDField . '&tbl=' .  $myTable . '">Add A Record</a>';
         if($foundRecord == 0)
         {
             print '<tr><td colspan="' . $numColumns . '" align="center">No Records Found: '. $strNew . '</td></tr></table></div>';
         }else{
             if ($allowDelete==1)
             {
                 $numColumns -=1;
                 print '<tr class="evenRow"><td align="center"><input type="submit" value="Delete Checked"></td>';
                 print '<td colspan="' . $numColumns . '" align="center">'. $strNew . '</td></tr></table></div></form>';
             }else{
                 print "</table></div>";
             }
             if ($allowQuery==1) //allow select statement
             {
                 print '<div align="center"><form action="' . $myPage . '" method="post">TEST SQL SELECT STATEMENT HERE:<br />';
                 print '<textarea name="selSQL" cols="80" rows="4" wrap="virtual"></textarea><br /><input type="submit" value="TEST SQL STATEMENT" />';
                 print '<input type="hidden" name="act" value="selSQL" />';
                 print '</form></div>';
             }
             print '<div align="center">' . $myClear . $strAdd . ' &nbsp;<a href="' . $config->adminDashboard . '">Exit To Admin</a></div>';
            if($numrows > $rowsPerPage)
            {
                print '<div align="center">';
                $qstr = '&tbl=' . $myTable;
                if($selSQL!=""){$qstr .= "&sql=" . urlencode($selSQL);}//add sql statement to qstring
                showPaging($numrows,$rowsPerPage,$pageNum,$firstButton,$prevButton,$nextButton,$lastButton,false,true,$qstr);
                print '</div>';
             }
         }
    }
}
//EDIT DISPLAY***********************************
function editDisplay($myID,$myIDField)
{
   global $myConn,$myTable,$myPage;
   $selSQL = "select * from " . $myTable . " where " . $myIDField . " = " . $myID;
   //print $selSQL;
   //die();
   $result = @mysql_query($selSQL,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
   $numColumns = @mysql_num_fields($result);
   print "<div align=center><h1> Edit " . $myTable . "</h1></div>";
   print '<form action="' . $myPage . '" method="post">';
   print '<table class="edTBL">';
   $rowClass = "evenRow";
   while($row = mysql_fetch_row($result))
   {  //pull data from db array
     for($x = 0; $x < $numColumns; $x++)
     {
        $myValue = stripslashes($row[$x]);  //prevents blow out of code
        if($myValue!=""){$myValue = br2nl($myValue);} //newline chars appropriate
        $myValue = htmlspecialchars($myValue); //convert chars AFTER removing breaks!
        $fieldName = mysql_field_name($result,$x);
        $fieldType = mysql_field_type($result,$x);
        if($fieldName == $myIDField)
        {  //id field, no edit
            print '<tr class="headerRow">';
            print '<td align="right">' . $fieldName . '</td><td> ' . $myValue . '</td></tr>';
            print '<input type="hidden" name="idf" value="' . $fieldName . '">';
            print '<input type="hidden" name="id" value="' . $myValue . '">';
            print '<input type="hidden" name="act" value="edex">';
        }else{ //editable field
            $myField = createField($fieldType,$myValue,$fieldName);
            print '<tr class="' . $rowClass . '"><td align="right">' . $fieldName . '</td>';
            print '<td>' . $myField . '</td></tr>'; 
            print '<input type=hidden name="~' . $fieldName . '" value="' . $myValue . '">';
            print '<input type=hidden name="*' . $fieldName . '" value="' . $fieldType . '">';
            if($rowClass=="oddRow"){$rowClass="evenRow";}else{$rowClass="oddRow";}//alternate class/colors NOT WORKING!!
	}	
     }
   }
    print '<input type="hidden" name="tbl" value="' . $myTable . '" />';
    print '</table><div align="center"><input type="submit" value="Update Record"></form>';
    print '<a href="' . $myPage . '">Back Without Edit</a></div>';
 }
//ADD DISPLAY***********************************
 function addDisplay($myIDField)
{
   global $myConn,$myTable,$myPage;
   $selSQL = "select * from " . $myTable;
   $result = @mysql_query($selSQL,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
   $numColumns = mysql_num_fields($result);
   $rowClass = "oddRow";
   print '<div align="center"><h1> Add Record To ' . $myTable . '</h1></div>';
   print '<form action="' . $myPage . '" method="post">';
   print '<input type="hidden" name="tbl" value="' . $myTable . '" />';
   print '<table class="edTBL"><tr class="headerRow"><td align="center" colspan="' . $numColumns . '">Add Record</td></tr>';
	
    for($x = 0; $x < $numColumns; $x++)
    {//no while, because possibly no records!
        $fieldName = mysql_field_name($result,$x);
        $fieldType = mysql_field_type($result,$x);
        if($fieldName == $myIDField)
        {  //id field, do not display
            print '<input type="hidden" name="idf" value="' . $fieldName . '">';
        }else{ //add fields
            $myField = createField($fieldType,"",$fieldName);
            print '<tr class="' . $rowClass . '"><td align="right">' . $fieldName . '</td>';
            print '<td>' . $myField . '</td></tr>';	
            print '<input type="hidden" name="*' . $fieldName . '" value="' . $fieldType . '">';
        }
        if($rowClass=="oddRow"){$rowClass="evenRow";}else{$rowClass="oddRow";}
    }
    print '<input type="hidden" name="act" value="addex" />';
    print '</table><div align="center"><input type="submit" value="Add Record"></form>';
    print '<a href="' . $myPage . '">Back Without Add</a></div>';
 }
//ADD EXECUTE******************************
function addExecute()
{ 
    global $myConn;
    $nStr = ""; $vStr = ""; $recCount = 0;
    foreach ($_POST as $varName=> $value)
    {
        switch($varName)
        {
            case "idf":  //do nothing for these
            case "id":
            case "tbl":
            case "act":
                break;
            default:   //put data into variable for edit
                $value = addslashes($value);
                $strNewValue = $value;
                $strTest = substr($varName,0,1);  //check first char for ~ or *
                if($strTest != "*") //do NOT test asterisk
                {
                    $testType = "*" . $varName;
                    $testTypeValue = (trim($_POST[$testType]));
                    switch ($testTypeValue)
                    {
                        case "string":
                        case "text":
                        case "blob":
                             $nStr .= $varName . ", ";
                             if($value!=""){$value = addslashes(htmlspecialchars_decode($value));}
                             $vStr .= "'" .$value . "', ";
                             break;
                        case "date":
                        case "time":
                        case "timestamp":
                        case "datetime":
                            if($value!="")
                            {
                                    $nStr .= $varName . ", ";
                                            $vStr .= "'" .$value . "', ";
                            }
                            break;   
                        default:
                            $nStr .= $varName . ", ";
                            if($value!=""){$vStr .= $value . ", ";}else{$vStr .= "0, ";}//in case of no entry on numeric
                    }
                     $recCount++;
                }
         }
    }
    $nStr = substr($nStr,0,strlen($nStr)- 2); //trim last comma, space
    $vStr = substr($vStr,0,strlen($vStr)- 2);
    //if(strLen($vStr) < ($recCount*4)){header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']). "?msg=1";} //no update, redirect
    $addSQL = "insert into " . $_POST['tbl'] . " (" . $nStr . ") values (" . $vStr . ")";
    @mysql_query($addSQL,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
    tableDisplay("",$_POST['tbl'],"Record Successfully Added");
}

//DELETE EXECUTE*****************************
function deleteExecute()
{//re-write this as a loop for select vs. radio button for multiple delte.
    global $myConn;
    //$delSQL = "delete from " . $_POST['tbl'] . " where " . $_POST['idf'] . "=" . $_POST['id'];
    $Delete = "";//delete checkbox
    while (list ($key,$val) = @each($_POST['Delete'])) { 
        if($Delete == ""){$Delete = $val;}else{$Delete .= ",$val";} 
    } 
    $delSQL = "delete from " . $_POST['tbl'] . " where " . $_POST['idf'] . " in (" . $Delete . ")";
    @mysql_query($delSQL,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
    tableDisplay("",$_POST['tbl'],"Records Successfully Deleted");
}

//EDIT EXECUTE ******************************
function editExecute()
{
    global $myConn;
    $nStr = ""; //declare ahead
    foreach ($_POST as $varName=> $value)
    {
       switch($varName)
       {
        case "idf":  //do nothing for these
        case "id":
        case "act":
        case "tbl":
        break;
        default:   //put data into variable for edit
           $strNewValue = $value;
           $strTest = substr($varName,0,1);  //check first char for ~ or *
           if(($strTest != "~")&&($strTest != "*")) //do NOT test tilde & asterisk
           {
           $testField = "~" . $varName;
           $testValue = (trim($_POST[$testField]));
           $testType = "*" . $varName;
           $testTypeValue = (trim($_POST[$testType]));
              if($testValue != $strNewValue)
               {  //test value
                   switch ($testTypeValue)
                   {
                      case "string":
                      case "text":
                      case "blob":
                         if($value!=""){$value = addslashes(htmlspecialchars_decode($value));}
                         $nStr .= $varName . " = '" . $value . "', ";
                         break;
                      case "date":
                      case "time":
                      case "timestamp":
                      case "datetime":
                         if($value!=""){
                             if($value=="NOW()"){
                                     $nStr .= $varName . " = " . $value . ", ";
                             }else{
                                     $nStr .= $varName . " = '" . $value . "', ";	
                             }	
                         }
                         break;     
                      default:
                         $nStr .= $varName . " = " . $value . ", ";
                   }
               }
           }
       }
    }
    $upSQL = substr($nStr,0,strlen($nStr)- 2);
    if(strLen($upSQL) < 3){header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']). "msg=1";} //no update, redirect
    $upSQL = "update " . $_POST['tbl'] . " set " . $upSQL . " where " . $_POST['idf'] . " = " . $_POST['id'];
    @mysql_query($upSQL,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
    tableDisplay("",$_POST['tbl'],"Record Successfully Edited");
} 
//CREATE FIELD FOR DISPLAY FUNCTION FIELDS
function createField($fieldType,$myValue,$fieldName)
{//default width
    switch ($fieldType)
    {
    case "string":
        $myLen = 60;
        break;
    case "text":
    case "blob":
        $myLen = 60;
        if(strLen($myValue) > 25){$rownumber =(round(.02*(strLen($myValue))) + 1);}else{$rownumber = 3;}
        $myLen = '<textarea  rows="' .  $rownumber . '" cols="60" wrap name="' . $fieldName . '">' . $myValue .  '</textarea>(' . $fieldType . ')'; 
        return $myLen;
        break;
    case "date":
    case "time":
    case "timestamp":
    case "datetime":
        $myLen = 20;
        //FIX DATETIME HERE, ADD PROPER ZERO SPACING, ETC FOR EMPTY!!
        break;
    default:
        $myLen = 15;
    }
    return '<input type="text" name="' . $fieldName . '" value="' . $myValue . '" size="' . $myLen . '" />(' . $fieldType . ')'; 
}
//function grabID
function grabPrimary($myTable)
{
    global $myConn;
    $query="DESC " . $myTable;
    $result=@mysql_query($query,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
    while ($row=mysql_fetch_array($result))
    {
        if ($row['Type']="PRI")
        {
            return $row['Field'];
            exit;
        }
    }
}

function getDir($myDirection,$upButton,$downButton)
{//get direction image
    if($upButton != ""){$upButton = '<img src="' . $upButton . '" border="none">';}else{$upButton = "<b>^</b>";}
    if($downButton != ""){$downButton = '<img src="' . $downButton . '" border="none">';}else{$downButton = "<b>v</b>";}
    if($myDirection == "desc"){return $downButton;}else{return $upButton;}
}

function buildQS($myDirection,$fieldName,$currentSort)
{//build qstring for sort on field
    global $myTable;
    if($fieldName==$currentSort){//flip direction of current field only
        if($myDirection =="desc"){$myDirection="asc";}else{$myDirection="desc";}//flip direction
    }else{
    //default to asc:
        $myDirection = "asc";	
    }
    $qs = "?of=" . $fieldName . "&od=" . $myDirection;
    if($myTable != ''){$qs .= '&tbl=' . $myTable;}
    return $qs;	
}

/**
 * showPaging() builds paging buttons or characters
 *
 *<code>
 * showPaging($numrows,$rowsPerPage,$pageNum,"",'<img src="images/arrow_prev.gif" border="0" />','<img src="images/arrow_prev.gif" border="0" />',"",false,false,"");
 *</code>
 *
 * @param integer $numrows total number of records returned
 * @param integer $rowsPerPage String holding possible alphanum string
 * @param integer $pageNum String holding possible alphanum string
 * @param string $first String holding possible alphanum string
 * @param string $prev String holding possible alphanum string
 * @param string $next String holding possible alphanum string
 * @param string $last String holding possible alphanum string 
 * @param string $showEmpty String holding possible alphanum string  
 * @param string $showFirstLast String holding possible alphanum string  
 * @param string $qstring String querystring to attach: "&tbl=Admin"
 * @return void   
 */
function showPaging($numrows,$rowsPerPage,$pageNum,$first,$prev,$next,$last,$showEmpty,$showFirstLast,$qstr){
    $maxPage = ceil($numrows/$rowsPerPage); //total pages
    if ($pageNum > 1)
    {
        $page = $pageNum - 1;
        $prev = ' <a href="' . THIS_PAGE . '?page=' . $page . $qstr . '">' . $prev . '</a> ';
        if($showFirstLast){$first = ' <a href="' . THIS_PAGE . '?page=1'  . $qstr .  '">' . $first . '</a> ';}else{$first="";}
    }
    else
    {
        if(!$showEmpty){$prev = '';}       // we're on page one, don't enable 'previous' link
        if(!$showEmpty){$first = '';} // nor 'first page' link
    }
    if ($pageNum < $maxPage)
    {// print 'next' link only if we're not on the last page
        $page = $pageNum + 1;
        $next = ' <a href="' . THIS_PAGE . '?page=' . $page . $qstr . '">' . $next . '</a> ';
        if($showFirstLast){$last = ' <a href="'. THIS_PAGE . '?page=' . $maxPage . $qstr . '">' . $last . '</a> ';}else{$last="";}
    }else{
        if(!$showEmpty){$next = '';}      // we're on the last page, don't enable 'next' link
        if(!$showEmpty){$last = '';} // nor 'last page' link
    }// print the page navigation link
    print '<span style="display:inline-block; vertical-align:middle">' . $first . $prev . ' Page <strong>' . $pageNum . '</strong> of <strong>' . $maxPage . '</strong> ' . $next . $last . '</span>';
}

function buildTableSelect($hideTables,$showTables)
{
    global $myConn,$myDB;
    if($hideTables != "")
    {
        $aHideTables = explode(",",$hideTables);
    }
    if($showTables != "")
    {
        $aShowTables = explode(",",$showTables);
    }
    print '<form><div align="center"><b>View Table:</b> <select name="tbl" onChange="jumpTo(this);">'; //add table selection
    print '<option value="">Select A Table</option>';

    $result = @mysql_query("SHOW TABLES FROM " . $myDB,$myConn) or die(trigger_error(mysql_error(), E_USER_ERROR));
    $match = false;
    while ($row = mysql_fetch_row($result))
    {
        
        if($showTables != "")
        {
            for($x=0;$x<count($aShowTables);$x++)
            {	   
                $filterLength = strlen($aShowTables[$x]);
                $filterTest = substr($row[0],0,$filterLength);
                if($filterTest==$aShowTables[$x])
                {
                    $match = true;
                    break 1;	
                }	
            }
                
            
            if($hideTables != "")
            {
                for($x=0;$x<count($aHideTables);$x++)
                {	   
                    if($row[0]==$aHideTables[$x])
                    {
                        $match = false;
                        break 1;	
                    }	
                }
            }
            
            if($match==true){print '<option value="' . $row[0] . '">' . $row[0] . '</option>';}
            $match = false;
        }else if($hideTables != ""){//hide tables only
            for($x=0;$x<count($aHideTables);$x++)
            {	   
                if($row[0]==$aHideTables[$x])
                {
                    $match = true;
                    break 1;	
                }	
            }
            if($match==false){print '<option value="' . $row[0] . '">' . $row[0] . '</option>';}
            $match = false;
        }else{//no show, no hide, show all!
            print '<option value="' . $row[0] . '">' . $row[0] . '</option>';
        }
    }
    print '</select></div></form>';
    @mysql_free_result($result);	
}
?>

