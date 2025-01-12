<?php

include_once('../config/connect.php');

if(isset($_POST['signup'])){
  $name = isset($_POST['name']) ? $_POST['name'] : '';
  $username = isset($_POST['username']) ? $_POST['username'] : '';
  $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
  $address = isset($_POST['address']) ? $_POST['address'] : '';
  $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
  $mobile = isset($_POST['number']) ? $_POST['number'] : '';
  $email = isset($_POST['email']) ? $_POST['email'] : '';

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
          if ($fileSize < 10000000) {
              $newFileName = uniqid('img_', true) . "." . $extension;
              $fileDestination = '../src/images/profile_picture/' . $newFileName;
              move_uploaded_file($fileTmpName, $fileDestination);
          } 
      } 
  }

  $query = "INSERT INTO users_tbl(name,username,password,address,email,birthday,mobile,profile_picture)VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $pdo->prepare($query);
  $params = [$name, $username, $password, $address, $email, $birthday, $mobile, $newFileName];

  if(!$stmt->execute($params)){ 
    die('Unable to create an account');
  } else {
    header("Location: ../src/pages/login.php");
    die();
  }
}

$pdo = null;

?>