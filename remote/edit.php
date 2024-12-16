<?php

include_once('../config/connect.php');

if(isset($_POST['edit_btn'])){
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $details = isset($_POST['details']) ? $_POST['details'] : '';
    $stock = isset($_POST['stock']) ? $_POST['stock'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : '';
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
    

    $query = "UPDATE product_tbl SET productName = '$name', productDetails = '$details', productPrice = '$price', productStocks = '$stock', productPicture = '$newFileName'";

    $result = $conn->query($query);

    if(!$newFileName === ''){
        if($result){
            header("Location: ../src/pages/home.php?updated");
        }
    } header("Location: ../src/pages/home.php?update=failed");
}

$conn->close();

?>