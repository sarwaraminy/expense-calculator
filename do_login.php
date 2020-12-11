<?php
session_start(); //start session.
include "config.php";

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
	$user = strip_tags(mysqli_real_escape_string($conn, trim($user)));
	$pass = strip_tags(mysqli_real_escape_string($conn, trim($pass)));
	$select_data = "select * from users where username='$user' and password='$pass'";
	$result = mysqli_query($conn,$select_data);
	$data = mysqli_fetch_array($result);
	if(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_assoc($result);
		//set session
		$_SESSION['user'] = $user;
		$_SESSION['tag'] = 'rpt';
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