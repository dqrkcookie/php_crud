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

  if($data && password_verify($password, $data->password) && $data->status !== 'Blocked'){
    $_SESSION['username'] = $username;
    echo "<script> window.location.href = '../src/pages/main.php'; </script>";
    die();
  } else if($data->status == 'Blocked'){
    header("Location: ../src/pages/blocked.php");
    die();
  } else {
    header("Location: ../index.php?login=failed");
    die();
  }

}

if(isset($_POST['loginAdmin'])){
  $username = $_POST['username'];
  $password = $_POST['password'];

  $admin = $pdo->query("SELECT * FROM shapi_admin WHERE username = '$username'")->fetch();

  if($admin){
    if($admin->password == $password){
      $_SESSION['admin'] = $admin->role;
      header("Location: ../src/pages/admin.php");
      die();
    }
  } else {
    header("Location: ../index.php");
    die();
  }
}

$pdo = null;

?>