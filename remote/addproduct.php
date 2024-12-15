<?php

include_once('../config/connect.php');

if(isset($_POST['add_product'])){
  $product_name = isset($_POST['product_name']) ? $conn->real_escape_string($_POST['product_name']) : '';

  $product_details = isset($_POST['product_details']) ? $conn->real_escape_string($_POST['product_details']) : '';

  $product_price = isset($_POST['product_price']) ? $conn->real_escape_string($_POST['product_price']) : '';

  $product_stocks = isset($_POST['product_stocks']) ? $conn->real_escape_string($_POST['product_stocks']) : '';

  $product_picture = isset($_POST['product_picture']) ? $conn->real_escape_string($_POST['product_picture'])  : '';

  $query = "INSERT INTO product_tbl(productName, productPrice, productDetails, productPicture, productStocks)VALUES('$product_name', '$product_price', '$product_details', '$product_picture', '$product_stocks')";

  $result = $conn->query($query);

  if(!$result){
    echo "<sript> window.alert('Failed to add product');
      window.location.href = '../src/pages/home.php';
    </script>";
  } else {
    header("Location: ../src/pages/home.php?add_item=success");
  }
}

$conn->close();
?>