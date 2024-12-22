<?php

include_once("../config/connect.php");

try{
    $checkout = $_GET['checkout'];
    $username = $_GET['username'];
    $address = $_GET['address'];
    $price = $_GET['payment'];
    $amount = $_GET['total'];
    $id = $_GET['id'];

    $query = "SELECT * FROM shapi_cart WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1, $username);
    $stmt->execute();

    $itemsList = [];

    if ($checkout === 'true') {
        $items = $stmt->fetchAll();
        
        foreach($items as $i) {
            array_push($itemsList, $i->name . ' ' . $i->quantity);
        }

        $toJson = json_encode($itemsList);

        $query = "INSERT INTO placed_order(name,quantity,price,username,amount,uniqOrderId) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
    
        $amount = 0;
        foreach ($items as $item) {
            $amount += $item->price * $item->quantity; 
        }
    
        foreach ($items as $item) {
            $params = [$item->name, $item->quantity, $item->price, $item->username, $amount, $id];
            if (!$stmt->execute($params)) {
                echo "<script>window.alert('Failed to checkout!');</script>";
            }
        }
    }

    $query1 = "SELECT * FROM placed_order WHERE username = ?";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(1, $username);
    $stmt1->execute();

    $data = $stmt1->fetchALL();
    $row_num = 0;
    foreach($data as $d){
      $row_num++;
    }

    $query2 = "INSERT INTO pending_orders(username,address,numberOfItems,payment,orderId, ListOfItems)VALUES(?, ?, ?, ?, ?, ?)";
    $stmt2 = $pdo->prepare($query2);
    $params = [$username, $address, $row_num, $price, $id, $toJson];
    $stmt2->execute($params);

    $query = "DELETE FROM shapi_cart WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1, $username);
    $stmt->execute();

} catch (PDOException $e){
    error_log('Database error: ' . $e->getMessage());
}

header("Location: ../src/pages/main.php?checkoutsuccess");

$pdo = null;

?>