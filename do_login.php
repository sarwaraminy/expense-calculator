<?php
session_start(); //start session.
//include "config.php";
include "ecalc_db.php";

//
$_SESSION['user'] = $_POST['username'];
if(!isset($_SESSION['user']))
{
    // not logged in
    header('Location: ecalc_login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$user = $_POST['username'];
	$pass = $_POST['password'];
	// connect to database
	$dbclas = new dbHandle();
	$execQ = $dbclas -> dbConnect();
	//do some sql injection handle
	$user = strip_tags(mysqli_real_escape_string($execQ, trim($user)));
	$pass = strip_tags(mysqli_real_escape_string($execQ, trim($pass)));
	//our query
	$select_data = "select * from users where username='$user' and password='$pass'";
	$execQ = $dbclas -> selectFreeRun($select_data);
	//get the rows
	$row_id='x ds s';//giv some junk value
	$row_pas=' s d xx ';// junk value
	while($row = $execQ -> fetch_assoc()){
		$row_id = $row['username'];
		$row_pas = $row['password'];
	}
	//$result = mysqli_query($conn,$select_data);
	//$data = mysqli_fetch_array($result);
	if($row_id == $user && $row_pas == $pass){
		//set session
		$_SESSION['user'] = $row_id;
		$_SESSION['tag'] = 'rpt';
		$execQ = $dbclas -> dbDisconnect();
		//catching user id by Session
        echo "<script>window.location = 'ecalc_main.php';</script>"; //Use script tag and close also
	}
	
	else {
		echo "ورود غیر موفقانه بود";
	}
	exit();
}
//else {echo "the set is not set";}
?>