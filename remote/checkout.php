<?php

require_once("../config/connect.php");

$checkout = $_GET['checkout'];
$username = $_GET['username'];

$query = "SELECT * FROM shapi_cart WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->bindParam(1, $username);
$stmt->execute();

if($checkout == true){
    $data = $stmt->fetchALL();
    $query = "INSERT INTO placed_order(name,quantity,price,username)VALUES(?, ?, ?, ?)";

    $stmt = $pdo->prepare($query);
    foreach($data as $item){
      $params = [$item->name, $item->quantity, $item->price, $item->username];

      if(!$stmt->execute($params)){
        echo "<script>window.alert('Failed to checkout!')</script>";
      }
    }
}

$query = "DELETE FROM shapi_cart WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->bindParam(1, $username);
$stmt->execute();
header("Location: ../src/pages/main.php?checkout=success");

$pdo = null;

?>