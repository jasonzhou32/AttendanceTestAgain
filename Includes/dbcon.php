<?php
	date_default_timezone_set('America/Chicago');
	$host = "localhost";
	$user = "root";
	$pass = "";
	$db = "attendancemsystem";
	
	$conn = new mysqli($host, $user, $pass, $db);
	if($conn->connect_error){
		echo "Seems like you have not configured the database. Failed To Connect to database:" . $conn->connect_error;
	}
?>