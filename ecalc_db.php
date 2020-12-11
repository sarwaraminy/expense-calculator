<?php 
include 'ecalc_dbconfig.php';

class dbHandle extends Dbconfig {
	//
	public $conn;
	public $dataSet;
	private $sqlQuery;
	
	 protected $dbname;
	 protected $serName;
	 protected $usrName;
	 protected $pasCode;
	 
	function dbHandle(){
		$this -> conn = NULL;
		$this -> dataSet = NULL;
		$this -> sqlQuery = NULL;
		  
		  $dbPara = new Dbconfig();
		  $this -> dbname  = $dbPara -> dbName;
		  $this -> serName = $dbPara -> serverName;
		  $this -> usrName = $dbPara -> userName;
		  $this -> pasCode = $dbPara -> passCode;
		  $dbPara = NULL;
	}
	
	function dbConnect(){
		$this -> conn = mysqli_connect($this -> serName, $this -> usrName, $this -> pasCode);
		mysqli_select_db($this -> conn, $this -> dbname);
		return $this -> conn;
	}
	
	function dbDisconnect(){
		$this -> conn = NULL;
		$this -> sqlQuery = NULL;
		$this -> dataSet = NULL;
		   
		   $this -> dbName = NULL;
		   $this -> serName = NULL;
		   $this -> usrName = NULL;
		   $this -> pasCode = NULL;
	}
	
	function selectAll($tableName){
		$this -> sqlQuery = 'select * from '. $this -> dbname.'.'.$tableName;
		$this -> dataSet = mysqli_query($this -> conn, $this -> sqlQuery);
		        return $this -> dataSet;
	}
	
	function selectWhere($tableName, $rowName, $operator, $value, $valType){
		$this -> sqlQuery = 'SELECT * FROM '.$tableName. ' WHERE '.$rowName.' '.$operator.' ';
		if($valType =='int'){
			$this -> sqlQuery .=$value;
		}
		else if($valType =='char'){
			$this -> sqlQuery .= "'".$value."'";
		}
		$this -> dataSet = mysqli_query($this -> conn, $this -> sqlQuery);
		$this -> sqlQuery = NULL;
		return $this -> dataSet;
	}
	
	function insertInto($tableName, $tblFles, $values){
		
		$this -> sqlQuery = 'INSERT INTO '.$tableName.'('.$tblFles.') VALUES '.$values.';';
		   //echo $this -> sqlQuery;
		//mysqli_query($this -> conn, $this -> sqlQuery);
		if (mysqli_query($this -> conn, $this -> sqlQuery)) {
			$resp = 'یک ریکار موفقانه وارد گردید!';
		}
		else {
			$resp = "Error: " . $this -> sqlQuery  . "<br>" . mysqli_error($this -> conn);
		}
		return $resp;
	}
	
	function selectFreeRun($query){
		$this -> dataSet = mysqli_query($this -> conn, $query);
		return $this -> dataSet;
	}
	
	function freeRun($query){
		return mysqli_query($this -> conn, $query);
	}
}
?>