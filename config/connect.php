<?php

	try{
		$conn = new mysqli('localhost', 'root', '', 'shopee_db', 3307);

		if($conn->connect_error){
			die('Failed connecting to Database' . $conn->connect_error);
		} 
	} catch(mysqli_sql_exception){
		die('Server is down');
	}

?>