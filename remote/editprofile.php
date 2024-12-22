<?php

include_once("../config/connect.php");
session_start();

if(isset($_POST['save'])){
  try{
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $birthday = $_POST['birthday'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_SESSION['username'];

    $newFileName = '';

    $file = $_FILES["profile"];
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
                $fileDestination = '../src/images/profile_picture/' . $newFileName;
                move_uploaded_file($fileTmpName, $fileDestination);
            } 
        } 
    }

    $query = "UPDATE users_tbl SET name = ?, address = ?, birthday = ?, mobile = ?,  email = ?, profile_picture = ? WHERE username = ?";

    $stmt = $pdo->prepare($query);
    $params = [$name, $address, $birthday, $mobile, $email, $newFileName, $username];

    if(!$stmt->execute($params)){
      echo "<script>window.alert('Failed to Update profile!');</script>";
    } else {
      header("Location: ../src/pages/main.php");
    }
  }catch(PDOException $e){
    error_log("Error updating profile: " . $e->getMessage());
  }
}

$pdo = null;

?>