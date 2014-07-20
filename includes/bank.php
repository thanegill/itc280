<?php
/*
	File : bank.php
	Date : 2009-Mar-07
	Loca : HOME
	Auth : W.Tharange Priyankara
	Task : Bank Object
*/
//function __autoload($class_name) {
//    require_once $class_name . '.php';
//}

define ("MYSQLHOST", "localhost");
define ("MYSQLUSER", "root");
define ("MYSQLPASS", "root");
define ("MYSQLDB", "ITC280");


include('dbconnector.php');

class bank{
	private $BANKCODE;
	private $BANKNAME;
	private $BRANCH;
	private $PHONE;
	private $fax;
	
	function __construct($bankcode,$bankname,$branch,$phone,$fax) {
		$this->BANKCODE=$bankcode;
		$this->BANKNAME=$bankname;
		$this->BRANCH=$branch;
		$this->PHONE=$phone;
		$this->FAX=$fax;
	}

	function __destruct() {
    	//print "Destroying " . $this->NAME . "\n";
   	}
   
   function save(){
   		$dbcon=new dbconnecter();
   		return $dbcon->execQuery($dbcon->getInsertQuery('0bank',$this->getInsertPropertyArray()));
   }
   
   function update(){
   		$dbcon=new dbconnecter();
   		return $dbcon->execQuery($dbcon->getUpdateQuery('0bank','BANKCODE',$this->BANKCODE,$this->getUpdatePropertyArray()));
   }
   
   
   function getInsertPropertyArray(){
   		$abc = array('BANKCODE'=>$this->BANKCODE,'NAME'=>$this->BANKNAME,'LOCATION'=>$this->BRANCH,'PHONE'=>$this->PHONE,'FAX'=>$this->FAX);
		return $abc;
   }
   
    function getUpdatePropertyArray(){
   		$abc = array('BANKCODE'=>$this->BANKCODE,'NAME'=>$this->BANKNAME,'LOCATION'=>$this->BRANCH,'PHONE'=>$this->PHONE,'FAX'=>$this->FAX);
		return $abc;
   }
}

$myBank = new bank('the bank code', 'bank name', 'branch', '1234567890', '0987654321');

var_dump($myBank);

$myBank->save();
?>