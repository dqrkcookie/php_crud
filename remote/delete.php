<?php

include_once('../config/connect.php');

try{
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    $query = "DELETE FROM product_tbl where productID = ?";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(1, $id);
    
    if($stmt->execute()){
      header("Location: ../src/pages/addproduct.php?delete_product=failed");
    } else {
      header("Location: ../src/pages/addproduct.php?delete_product=success");
    }
} catch (PDOException $e){
    error_log('Database error: ' . $e->getMessage);
}

$pdo = null;

?>