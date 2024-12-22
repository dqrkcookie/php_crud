<?php

include_once('../config/connect.php');
session_start();  

if(isset($_POST['login'])){
  $username = isset($_POST['username']) ? $_POST['username'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  $query = "SELECT * FROM users_tbl where username = :username";

  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  $data = $stmt->fetch();

  if($data && password_verify($password, $data->password)){
    $_SESSION['username'] = $username;
    echo "<script> window.location.href = '../src/pages/main.php'; </script>";
    die();
  } else {
    header("Location: ../index.php?login=failed");
    die();
  }

}

if(isset($_POST['loginAdmin'])){
  if($_POST['username'] === 'admin' && $_POST['password'] === 'admin'){
    $_SESSION['admin'] = $_POST['username'];
    header("Location: ../src/pages/admin.php");
    die();
  } else {
    header("Location: ../index.php");
    die();
  }
}

$pdo = null;

?>