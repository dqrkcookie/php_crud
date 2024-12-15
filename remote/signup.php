<?php

include_once('../config/connect.php');

is(isset($_POST['signup'])){
  $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
  $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
  $password = isset($_POST['password']) ? $conn->real_escape_string(password_hash($_POST['password'], PASSWORD_DEFAULT)) : '';

  $query = "INSERT INTO users_tbl(name,username,password)VALUES('$name', '$username', '$password')";

  $result = $conn->query($query);

  if(!$result){
    die('Unable to create account');
  } else {
    header("Location: ../index.php?signup=success");
  }
}

$conn->close();
?>