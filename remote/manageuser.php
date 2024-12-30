<?php

include_once("../config/connect.php");

$status = $_GET['block'];
$name = $_GET['name'];

if($status == 'yes'){
  $query = "UPDATE users_tbl SET status = ? WHERE name = ?";

  $stmt = $pdo->prepare($query);
  $params = ['Blocked', $name];
  $stmt->execute($params);

} else {
  $query = "UPDATE users_tbl SET status = ? WHERE name = ?";

  $stmt = $pdo->prepare($query);
  $params = ['Active', $name];
  $stmt->execute($params);
}

header("Location: ../src/pages/users.php");
$pdo = null;

?>