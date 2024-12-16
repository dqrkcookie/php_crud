<?php

include_once('../config/connect.php');
session_start();

if(isset($_POST['logout'])){
  session_unset();
  session_destroy();

  echo "<script>window.location.href = '../index.php';</script>";
}

$pdo = null;

?>