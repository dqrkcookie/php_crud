<?php

include_once('../config/connect.php');

if(isset($_POST['signup'])){
  $name = isset($_POST['name']) ? $_POST['name'] : '';
  $username = isset($_POST['username']) ? $_POST['username'] : '';
  $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';

  $query = "INSERT INTO users_tbl(name,username,password)VALUES(?, ?, ?)";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(1, $name);
  $stmt->bindParam(2, $username);
  $stmt->bindParam(3, $password);
  $stmt->execute();

  if(!$stmt->execute()){ 
    die('Unable to create an account');
  } else {
    header("Location: ../index.php?signup=success");
  }
}

$pdo = null;

?>