<?php

include("../config/connect.php");

try{
  $num = $_GET['num'];

  $query = "DELETE FROM shapi_cart WHERE num = ?";

  $stmt = $pdo->prepare($query);
  $stmt->bindParam(1, $num);
  $stmt->execute();
} catch (PDOException $e){
    error_log('Database error: ' . $e->getMessage());
}

header("Location: ../src/pages/main.php");

$pdo = null;

?>