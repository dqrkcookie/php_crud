<?php

include_once('../config/connect.php');

if(isset($_POST['add_product'])){
  $product_name = isset($_POST['product_name']) ? $conn->real_escape_string($_POST['product_name']) : '';

  $product_details = isset($_POST['product_details']) ? $conn->real_escape_string($_POST['product_details']) : '';

  $product_price = isset($_POST['product_price']) ? $conn->real_escape_string($_POST['product_price']) : '';

  $product_stocks = isset($_POST['product_stocks']) ? $conn->real_escape_string($_POST['product_stocks']) : '';

  $newFileName = '';

    $file = $_FILES["picture"];
    $fileName = $file["name"];
    $fileTmpName = $file["tmp_name"];
    $fileSize = $file["size"];
    $fileError = $file["error"];

    if ($fileError === 0) {
        $accepted_type = array('jpg', 'jpeg', 'gif', 'png', 'jfif');
        $getExtension = explode('.', $fileName);
        $extension = strtolower(end($getExtension));

        if (in_array($extension, $accepted_type)) {
            if ($fileSize < 1000000) {
                $newFileName = uniqid('img_', true) . "." . $extension;
                $fileDestination = '../src/images/' . $newFileName;
                move_uploaded_file($fileTmpName, $fileDestination);
            } 
        } 
    }

  $query = "INSERT INTO product_tbl(productName, productPrice, productDetails, productPicture, productStocks)VALUES('$product_name', '$product_price', '$product_details', '$newFileName', '$product_stocks')";

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