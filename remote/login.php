<?php

include_once('../config/connect.php');
session_start();  

if(isset($_POST['login'])){
  $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
  $password = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';

  $query = "SELECT * FROM users_tbl where username = '$username'";

  $result = $conn->query($query);

  $data = $result->fetch_array();

  if(password_verify($password, $data['password'])){
    $_SESSION['username'] = $username;
    header("Location: ../src/pages/home.php");
  } else {
    header("Location: ../index.php");
  }
}
?>