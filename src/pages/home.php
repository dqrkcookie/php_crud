<?php

include_once('../../config/connect.php');
session_start();

if(empty($_SESSION['username'])){
  header("Location: ../../index.php");
}

$query = "SELECT * FROM product_tbl ORDER BY productID";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shapi</title>
  <link rel="stylesheet" href="../style/home.css">
</head>
<body>
   <nav class="navbar">
        <div class="navbar-container">
            <div class="logo">
                <a href="index.php">Shapi</a>
            </div>
            <div class="nav-buttons">
                <button popovertarget="add_product" class="add-item-btn">+ Add Product</button>
            </div>
        </div>
    </nav>

    <div id="add_product" popover>
        <h1 style="text-align: center; color: black;">Add Product</h1>
        <form action="../../remote/addproduct.php" method="POST">
            <input type="text" placeholder="Product Name" name="product_name">
            <textarea placeholder="Product Details" name="product_details"></textarea>
            <input type="text" placeholder="Price" name="product_price">
            <input type="file" placeholder="Image URL" name="product_picture">
            <input type="text" placeholder="Stocks" name="product_stocks">
            <input type="submit" value="Add Product" name="add_product">
        </form>
    </div>

    <div class="list_container">
      <table>
          <thead>
            <tr>
              <td>Name</td>
              <td>Picture</td>
              <td>Details</td>
              <td>Price</td>
              <td>Stocks</td>
              <td>Actions</td>
            </tr>
          </thead>
          <tbody>
          <?php while($data = $result->fetch_array()) {?>
            <tr> 
              <td><?php echo $data['productName']; ?></td> 
              <td id="img"><img src="../images/<?php echo $data['productPicture']; ?>" alt="Product Image" class="product-img"></td> 
              <td><?php echo $data['productDetails']; ?></td> 
              <td><?php echo '₱' . $data['productPrice']; ?></td> 
              <td><?php echo $data['productStocks']; ?></td>
              <td>
                <ul id="actions">
                  <li><button class="btn">View</button></li>
                  <li><button class="btn">Edit</button></li>
                  <li><a href="../../remote/delete.php?id=<?php echo $data['productID']; ?>"><button class="btn">Delete</button></a></li>
                </ul>
              </td>
            </tr>
            <?php  }?>
          </tbody>
      </table>
    </div>
</body>
</html>