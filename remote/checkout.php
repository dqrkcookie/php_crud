<?php

include_once("../config/connect.php");

try {
   $checkout = $_GET['checkout'];
   $username = $_GET['username'];
   $address = $_GET['address'];
   $price = $_GET['payment'];
   $amount = $_GET['total'];
   $id = $_GET['id'];

   $query = "SELECT * FROM shapi_cart WHERE username = ?";
   $stmt = $pdo->prepare($query);
   $stmt->execute([$username]);

   $itemsList = [];
   $items = $stmt->fetchAll();

   if ($checkout === 'true' && !empty($items)) {
       foreach ($items as $item) {
           $productQuery = $pdo->prepare("SELECT * FROM product_tbl WHERE productName = ?");
           $productQuery->execute([$item->name]);
           $product = $productQuery->fetch();

           if ($product) {
               $newQty = $product->totalStocks - $item->quantity;

               if ($newQty < 0) {
                   echo "<script>alert('Insufficient stocks. Reduce the quantity to checkout.');
                   window.location.href = '../src/pages/main.php?checkout=failed';</script>";
                   exit();
               } else {
                   $updateQuery = $pdo->prepare("UPDATE product_tbl SET totalStocks = ? WHERE productName = ?");
                   $updateQuery->execute([$newQty, $item->name]);
               }

               array_push($itemsList, $item->name . ' ' . $item->quantity);
           }
       }

       $toJson = json_encode($itemsList);

       $query = "INSERT INTO placed_order(name, quantity, price, username, amount, uniqOrderId) VALUES (?, ?, ?, ?, ?, ?)";
       $stmt = $pdo->prepare($query);

       foreach ($items as $item) {
           $params = [$item->name, $item->quantity, $item->price, $username, $amount, $id];
           if (!$stmt->execute($params)) {
               echo "<script>alert('Failed to check out!');</script>";
               exit();
           }
       }

       $query2 = "INSERT INTO pending_orders(username, address, numberOfItems, payment, orderId, ListOfItems) VALUES (?, ?, ?, ?, ?, ?)";
       $stmt2 = $pdo->prepare($query2);
       $params = [$username, $address, count($items), $price, $id, $toJson];
       $stmt2->execute($params);

       $query = "DELETE FROM shapi_cart WHERE username = ?";
       $stmt = $pdo->prepare($query);
       $stmt->execute([$username]);

   }

   header("Location: ../src/pages/main.php?checkoutsuccess");
   exit();

} catch (PDOException $e) {
   error_log('Database error: ' . $e->getMessage());
   echo "<script>alert('Something went wrong. Please try again later.');</script>";
}

$pdo = null;

?>
