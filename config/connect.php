<?php

try{
	$username = 'root';
	$password = '';
	$host = 'localhost';
	$dbname = 'shopee_db';
	$port = 3307;

	$dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname;

	$pdo = new PDO($dsn, $username, $password);
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

	if(!$pdo){
		die('Connection close!');
	}
}catch(PDOException $e){
	error_log('Connection failed: ' . $e->getMessage());
	die();
}

?>