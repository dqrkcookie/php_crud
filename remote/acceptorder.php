<?php

include_once("../config/connect.php");
session_start();

try{
    $accept = $_GET['accept'];
    $id = $_GET['orderid'];
    $username = $_GET['username'];
    $payment = $_GET['payment'];
    $items = $_GET['list'];
    
    $query = "INSERT INTO order_notifications (username, items, amount, order_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, $items, $payment, $id]);

    $query = "UPDATE accepted_orders SET accept = ? WHERE id = ?";
    $stmt1 = $pdo->query('SELECT * FROM pending_orders');
    $query2 = "DELETE FROM pending_orders WHERE username = ? AND payment =?";
    $query3 = "INSERT INTO accepted_orders(username,address,numberOfItems,payment,accept)VALUES(?,?,?,?,?)";
    
    $data = $stmt1->fetch();
    
    $stmt3 = $pdo->prepare($query3);
    $params1 = [$data->username, $data->address, $data->numberOfItems, $data->payment, $accept];
    $stmt3->execute($params1);
    
    $stmt = $pdo->prepare($query);
    $stmt2 = $pdo->prepare($query2);
    $params = [$accept, $id];
    $stmt2->bindParam(1, $username);
    $stmt2->bindParam(2, $payment);
    $stmt->execute($params);
    $stmt2->execute();
} catch (PDOException $e){
    error_log('Database error: ' . $e->getMessage());
}

header("Location: ../src/pages/allorders.php?items=" . urlencode($items));

$pdo = null;

?>