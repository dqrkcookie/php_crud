<?php

include("../config/connect.php");
session_start();

try{
    $id = $_GET['id'];
    $name = $_GET['name'];
    $amount = $_GET['amount'];
    $status = $_GET['status'];
    
    $query = "INSERT INTO transaction_history(orderID, name, amount, transactionDate, status)VALUES(?,?,?,NOW(),?)";
    $query1 = "DELETE FROM placed_order WHERE username = ? AND amount = ?";
    $query3 = "UPDATE accepted_orders SET accept = ? WHERE username = ? ORDER BY id DESC LIMIT 1";
    
    $stmt = $pdo->prepare($query);
    $stmt1 = $pdo->prepare($query1);
    $stmt3 = $pdo->prepare($query3);

    $params = [$id, $name, $amount, $status];
    $stmt1->bindParam(1, $name);
    $stmt1->bindParam(2, $amount);

    if($status == 'success'){
      $params1 = ['completed', $name];
    } else {
      $params1 = ['failed', $name];
    }

    $stmt->execute($params);
    $stmt1->execute();
    $stmt3->execute($params1);
} catch (PDOException $e){
    error_log('Database error:' . $e->getMessage());
}

header("Location: ../src/pages/main.php?order=completed");

$pdo = null;

?>