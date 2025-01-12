<?php

include_once('../config/connect.php');
session_start();

if(isset($_POST['logout'])){
  unset($_SESSION['username']);

  echo "<script>window.location.href = '../src/pages/login.php';</script>";
  die();
} else if(isset($_POST['adminLogout'])){
  unset($_SESSION['admin']);

  echo "<script>window.location.href = '../src/pages/login.php';</script>";
  die();
}

$pdo = null;

?>