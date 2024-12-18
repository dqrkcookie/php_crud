<?php

include_once("../config/connect.php");

$name = $_GET['name'];
$price = $_GET['price'];
$quantity = $_GET['quantity'];
$username = $_GET['username'];

$query = "SELECT * FROM shapi_cart WHERE name = ? AND username = ?";
$stmt = $pdo->prepare($query);
$stmt->bindParam(1, $name);
$stmt->bindParam(2, $username);
$stmt->execute();
$product = $stmt->fetch();

if ($product) {
    $newQuantity = $product->quantity + $quantity;
    $updateQuery = "UPDATE shapi_cart SET quantity = ? WHERE name = ? AND username = ?";
    $stmt = $pdo->prepare($updateQuery);
    $params = [$newQuantity, $name, $username];
    $stmt->execute($params);
} else {
    $query = "INSERT INTO shapi_cart (name, quantity, price, username) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $params = [$name, $quantity, $price, $username];
        
    if (!$stmt->execute($params)) {
        echo "<script>alert('Unable to add this product');</script>";
    } 
}

echo "<script>window.alert('Added item successfully!');
  window.location.href = '../src/pages/main.php';
</script>";

$pdo = null;

?>
