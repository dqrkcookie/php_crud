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
                if ($fileSize < 500000) {
                    $newFileName = uniqid('img_', true) . "." . $extension;
                    $picture = $newFileName;
                    $fileDestination = '../src/images/' . $newFileName;
                    move_uploaded_file($fileTmpName, $fileDestination);
                }
            }
        }
    }

    if ($picture) {
        $query = "UPDATE product_tbl SET productName = '$name', productDetails = '$details', productPrice = '$price', productStocks = '$stock', productPicture = '$picture' WHERE productID = '$productID'";
    } else {
        $query = "UPDATE product_tbl SET productName = '$name', productDetails = '$details', productPrice = '$price', productStocks = '$stock' WHERE productID = '$productID'";
    }

    $result = $conn->query($query);

    if ($result) {
        header("Location: ../src/pages/home.php?updated");
        exit();
    } else {
        echo "Failed to update product.";
    }
}

$conn->close();

?>
