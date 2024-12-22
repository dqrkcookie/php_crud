<?php

include_once('../config/connect.php');

if (isset($_POST['edit_btn'])) {
    $productID = isset($_POST['productID']) ? $_POST['productID'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $details = isset($_POST['details']) ? $_POST['details'] : '';
    $stock = isset($_POST['stock']) ? $_POST['stock'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
    $picture = '';

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
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
                if ($fileSize < 10000000) {
                    $newFileName = uniqid('img_', true) . "." . $extension;
                    $picture = $newFileName;
                    $fileDestination = '../src/images/' . $newFileName;
                    move_uploaded_file($fileTmpName, $fileDestination);
                }
            }
        }
    }

    if ($picture) {
        $query = "UPDATE product_tbl SET productName = ?, productDetails = ?, productPrice = ?, productStocks = ?, productPicture = ? WHERE productID = ?";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $details);
        $stmt->bindParam(3, $price);
        $stmt->bindParam(4, $stock);
        $stmt->bindParam(5, $picture);
        $stmt->bindParam(6, $productID);
    } else {
        $query = "UPDATE product_tbl SET productName = ?, productDetails = ?, productPrice = ?, productStocks = ? WHERE productID = ?";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $details);
        $stmt->bindParam(3, $price);
        $stmt->bindParam(4, $stock);
        $stmt->bindParam(5, $productID);
    }

    if ($stmt->execute()) {
        header("Location: ../src/pages/addproduct.php");
        exit();
    } else {
        echo "Failed to update product.";
    }
}

$pdo = null;

?>
