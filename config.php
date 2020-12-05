<?php
//-----------------------------------------------------------------------------------------
// this program is used for database information
//-----------------------------------------------------------------------------------------
$servername = "localhost";
$username = "root";
$password = "aaAA11!!";
$dbname   = "ecalc";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
?>