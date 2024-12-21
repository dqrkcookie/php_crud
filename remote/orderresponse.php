<?php

include("../config/connect.php");
session_start();

try{
    $id = $_GET['id'];
    $name = $_GET['name'];
    $amount = $_GET['amount'];
    $status = $_GET['status'];
    $isRead = $_GET['isRead'];
    
    $query = "INSERT INTO transaction_history(orderID, name, amount, transactionDate, status)VALUES(?,?,?,NOW(),?)";
    $query1 = "DELETE FROM placed_order WHERE username = ? AND amount = ?";
    $query3 = "UPDATE accepted_orders SET accept = ? WHERE username = ? AND payment = ?";
    $query4 = "UPDATE order_notifications SET is_read = ? WHERE username = ? AND amount = ?";
    
    $stmt = $pdo->prepare($query);
    $stmt1 = $pdo->prepare($query1);
    $stmt3 = $pdo->prepare($query3);
    $stmt4 = $pdo->prepare($query4);

    $params = [$id, $name, $amount, $status];
    $stmt1->bindParam(1, $name);
    $stmt1->bindParam(2, $amount);
    $parameters = [$isRead, $name, $amount];

    if($status == 'success'){
      $params1 = ['completed', $name, $amount];
    } else {
      $params1 = ['failed', $name, $amount];
    }

    $stmt->execute($params);
    $stmt1->execute();
    $stmt3->execute($params1);
    $stmt4->execute($parameters);
} catch (PDOException $e){
    error_log('Database error:' . $e->getMessage());
}

header("Location: ../src/pages/main.php?order=completed");

$pdo = null;

?>