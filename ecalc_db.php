<?php 
include config.php

class dbHandle {
	//create an insert function
	function excuteQuery($query){
		$query = mysqli_real_escape_string($query);
		if(mysqli_query($conn, $query)){
			echo "New record created successfully";
		}
		else {
			echo "Error: " . $query . "<br>" . mysqli_error($conn);
		}
		//close connection 
		mysqli_close($conn);
	}
	
}
?>