<?php
/*
	File : dbconnector.php
	Date : 2009-Feb-23
	Loca : Home
	Auth : W.Tharange Priyankara
	Task : Connect and Execute DB query
*/

	
class dbconnecter{
	private $dbLastError;
	private $conn;
	private $user;

	function __construct(){
		$dbcon = new mysqli(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);
		$this->conn = $dbcon;

	}
	
   	function execQuery($query){
   		return $this->conn->real_query($query);
   		
   	}
   	
   	function execQueryUlog($query){//echo $query; die();
   		return $this->conn->real_query($query);
   		
   	}
   	
   	function getLastError(){
   		return $this->conn->error;
   	}
   	
   	function getInsertQuery($table_name,$array_object){
   		if(is_array($array_object)){
	   		$sql_qry='';
	        $sql_val='';
	        $i=0;
	        while ($i<count($array_object)){
	            $sql_qry.= key($array_object);
	            $key=key($array_object);
	            $sql_val.='"'.$array_object[$key].'"';
	
	            if ($i<>count($array_object)-1) {
		            $sql_qry.=',';
		            $sql_val.=',';
	            }
	            
	            next($array_object);
	            $i=$i+1;
	        }
	        
	        return 'INSERT INTO '.$table_name.' ('.$sql_qry.') VALUES ('.$sql_val.')';
		}
   	}
   	
   	function getUpdateQuery($table_name,$search_key,$value,$array_object,$and=''){
   		if(is_array($array_object)){
	   		$sql_qry='';
	        $sql_val='';
			$i=0;
			
			while ($i<count($array_object)){
				$key=key($array_object);
				$sql_val='"'.$array_object[$key].'"';
				$sql_qry.= key($array_object).'='.$sql_val;
				
				if ($i<>count($array_object)-1){
					$sql_qry.=',';
				}
				
	    		next($array_object);
				$i=$i+1;
			}
			
			$sql_qry='UPDATE '.$table_name.' SET '.$sql_qry.' WHERE '.$search_key.'="'.$value.'"';
			if (!$and==''){
				$sql_qry.=$and;
			}
			
			return $sql_qry;
		}
   	}
   	
   	function getDeleteQuery($table_name,$search_key,$value,$and=''){
		$sql_qry='DELETE FROM '.$table_name.'  WHERE '.$search_key.'="'.$value.'"';
		
		if (!$and==''){
			$sql_qry.=$and;
		}
		
		return $sql_qry;
	}

   	public function execQueryRes($sql){
		$result = $this->conn->query($sql);
		return $result;
		$mysqli->close();
	}   	
   	
   	public function select($table,$key='',$value='',$and=''){
		$sql="";
		if ($key==''){
			$sql='select * from '.$table  ;
		}else{
			$sql='select * from `'.$table . '` where ' . $key .'="' .$value. '"' ;
		}
		
		if (!$and==''){
			$sql.=$and;
		}
		
		$result = $this->conn->query($sql);
		return $result;
	}
	
	
   	public function selectManu($table,$where=''){
		$sql="";
		$sql='select * from '.$table . ' where ' . $where  ;
		$result = $this->conn->query($sql);
		return $result;
	}
	
	public function select_like($table,$key,$value,$and=''){
		$sql="";
		if ($key==''){
			$sql='select * from '.$table  ;
		}else{
			$sql='select * from '.$table . ' where ' . $key .' like "%' .$value. '%"' ;
		}
		
		if (!$and==''){
			$sql.=$and;
		}
		
		$result = $this->conn->query($sql);
		return $result;
	}
	
	public function autoCommitOff(){
		$this->conn->autocommit(False);
	}
	
	public function doCommit(){
		$this->conn->commit();
	}
	
	public function doRollback(){
		$this->conn->rollback();
	}
	
   	function __destruct() {
    	$this->conn->close();
   	}
}
?>