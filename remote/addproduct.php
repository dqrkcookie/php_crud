<?php

include_once('../config/connect.php');

if(isset($_POST['add_product'])){
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $product_details = isset($_POST['product_details']) ? $_POST['product_details'] : '';
    $product_price = isset($_POST['product_price']) ? $_POST['product_price'] : '';
    $product_stocks = isset($_POST['product_stocks']) ? $_POST['product_stocks'] : '';
    $product_category = isset($_POST['category']) ? $_POST['category'] : '';
    $show = isset($_POST['show']) ? $_POST['show'] : '';

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
            if ($fileSize < 5000000) {
                $newFileName = uniqid('img_', true) . "." . $extension;
                $fileDestination = '../src/images/' . $newFileName;
                move_uploaded_file($fileTmpName, $fileDestination);
            } 
        } 
    }

    $query = "INSERT INTO product_tbl(productName, productPrice, productDetails, productPicture, productStocks, category, showProduct)VALUES(?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1, $product_name);
    $stmt->bindParam(2, $product_price);
    $stmt->bindParam(3, $product_details);
    $stmt->bindParam(4, $newFileName);
    $stmt->bindParam(5, $product_stocks);
    $stmt->bindParam(6, $product_category);
    $stmt->bindParam(7, $show);

    if(!$stmt->execute()){
      echo "<script> window.alert('Failed to add product');
        window.location.href = '../src/pages/addproduct.php';
      </script>";
    } else {
      header("Location: ../src/pages/addproduct.php?added");
    }
}

$pdo = null;

?>