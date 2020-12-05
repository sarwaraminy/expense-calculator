<?php
session_start(); //start session.
if(!isset($_SESSION['user']))
{
    // not logged in
    header('Location: ecalc_login.php');
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
 <title> سافتویرمحاسب</title>
 <link href="ecalc.login.css" type="text/css" rel="stylesheet">
 <script src="jQuery/js/jquery-1.11.2.min.js"></script>
 <script src="jQuery/js/jquery-ui-1.11.2.min.js"></script>
 <script>
 $(document).ready(function() {
	 $("#LogOut").click(function() {
		 document.location = 'ecalc_logout.php';
		 
	 });
 });
 </script>
</head>
<body>
<div id="buttons"><div id="LogOut">Sign out</div></div>
<p>welcom to ---</p>
</body>
</html>
