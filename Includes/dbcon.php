<?php
	date_default_timezone_set('America/Chicago');
	// $host = "localhost";
	// $user = "root";
	// $pass = "";
	// $db = "attendancemsystem";

	// Retrieve database connection details from environment variables
	$host = getenv("DB_HOST");
	$user = getenv("DB_USER");
	$pass = getenv("DB_PASS");
	$dbname = getenv("DB_NAME");
	$port = getenv("DB_PORT")
	
	// $conn = new mysqli($host, $user, $pass, $db, $port);
	// if($conn->connect_error){
	// 	echo "Seems like you have not configured the database. Failed To Connect to database:" . $conn->connect_error;
	// }

	$conn = mysqli_init();
	mysqli_real_connect($conn, "mynewtestserver.mysql.database.azure.com", "xilinnewtester", "MyTesterPass123", "your_database_name", 3306);
?>
