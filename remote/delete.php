<?php

include_once('../config/connect.php');

$id = isset($_GET['id']) ? $_GET['id'] : '';

$query = "DELETE FROM product_tbl where productID = '$id'";

$result = $conn->query($query);

if(!$result){
  header("Location: ../src/pages/home.php?delete_product=failed");
} else {
  header("Location: ../src/pages/home.php?delete_product=success");
}

$conn->close();
?>