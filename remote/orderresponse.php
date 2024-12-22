<?php

include("../config/connect.php");
session_start();

try {
    $id = $_GET['id'];
    $name = $_GET['name'];
    $amount = $_GET['amount'];
    $status = $_GET['status'];
    $isRead = $_GET['isRead'];

    $query4 = "UPDATE order_notifications SET is_read = ? WHERE username = ? AND order_id = ?";
    $stmt4 = $pdo->prepare($query4);
    $stmt4->execute([$isRead, $name, $id]);

    $query = "INSERT INTO transaction_history(orderID, name, amount, transactionDate, status) VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id, $name, $amount, $status]);

    $query1 = "DELETE FROM placed_order WHERE username = ? AND uniqOrderId = ?";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute([$name, $id]);

    $status_value = ($status == 'success') ? 'completed' : 'failed';
    $query3 = "UPDATE accepted_orders SET accept = ? WHERE username = ? AND uniqId = ?";
    $stmt3 = $pdo->prepare($query3);
    $stmt3->execute([$status_value, $name, $id]);

    $pdo->commit();
    
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
}

header("Location: ../src/pages/main.php?responded");
exit();

$pdo = null;

?>
