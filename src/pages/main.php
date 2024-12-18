<?php

include_once("../../config/connect.php");
session_start();

if(empty($_SESSION['username'])){
  header("Location: ../../index.php");
}

$username = $_SESSION['username'];

$query = "SELECT * FROM product_tbl ORDER BY productID";
$query2 = "SELECT * FROM shapi_cart WHERE username = ?";
$query3 = "SELECT * FROM placed_order WHERE username = ?";

$stmt = $pdo->prepare($query);
$stmt2 = $pdo->prepare($query2);
$stmt3 = $pdo->prepare($query3);
$stmt2->bindParam(1, $username);
$stmt3->bindParam(1, $username);

$stmt->execute();
$stmt2->execute();
$stmt3->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shapi</title>
  <link rel="stylesheet" href="../style/main.css">
  <script src="https://kit.fontawesome.com/b70669fb91.js" crossorigin="anonymous"></script>
</head>
<body>

<nav>
  <ul>
    <li>Shapi</li>
    <li><input type="text" placeholder="Search product"></li>
    <li><button popovertarget="cart" id="cart_btn"><i class="fa-solid fa-cart-shopping fa-xl" style="color: #5b6edb;"></i></button></li>
    <li><button id="place" popovertarget="orders">My Orders <i class="fa-solid fa-bag-shopping" style="color: #f0f0f0;"></i></button></li>
    <li>
      <form action="../../remote/logout.php" method="POST">
        <button type="submit" name="logout">Log out</button>
      </form>
    </li>
  </ul>
</nav>

<div class="cart" id="cart" popover>
  <table>
    <thead>
      <tr>
        <td>Name</td>
        <td>Quantity</td>
        <td>Price</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <h2 class="cartTitle">Shopping Cart</h2>
      </tr>                                                     
      <?php while($data = $stmt2->fetch()) { ?>
        <tr>
          <td><?php echo $data->name ?></td>
          <td><?php echo $data->quantity ?></td>
          <td><?php echo $data->price ?></td>
        </tr>
      <?php } ?>
      <?php $stmt2->execute() ?>
      <?php $data = $stmt2->fetch() ?>
      <?php if(!empty($data)) { ?>
        <tr>
          <td class="checkout"><a href="../../remote/checkout.php?checkout=true&username=<?php echo $username ?>">Checkout now</a></td>
          <td>Total</td>
          <td class="total-price">999</td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="orders" id="orders" popover>
  <table>
    <thead>
      <tr>
        <td>Name</td>
        <td>Quantity</td>
        <td>Price</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <h2 class="cartTitle">Ordered Products</h2>
      </tr> 
      <?php while($data = $stmt3->fetch()) { ?>
        <tr>
          <td><?php echo $data->name ?></td>
          <td><?php echo $data->quantity ?></td>
          <td><?php echo $data->price ?></td>
        </tr>
      <?php } ?>
      <?php $stmt3->execute() ?>
      <?php $data = $stmt3->fetch() ?>
      <?php if(!empty($data)) { ?>
        <tr>
          <td>*</td>
          <td>Please pay <span class="total-price">999</span> upon delivery</td>
          <td>*</td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
  
<section>
  <?php while($data = $stmt->fetch()) { ?>
  <div class="item" id="view_product-">
    <span>Name: <?php echo $data->productName ?></span>
    <img src="../images/<?php echo $data->productPicture ?>"></img>
    <span>Details: <?php echo $data->productDetails ?></span>
    <span>Price: <?php echo $data->productPrice ?></span>
    <a href="../../remote/addtocart.php?name=<?php echo $data->productName ?>&image=<?php echo $data->productPicture ?>&price=<?php echo $data->productPrice ?>&quantity=1&username=<?php echo $username ?>" title="Add to cart"><button>Add to cart <i class="fa-solid fa-cart-shopping fa-sm" style="color: #f0f0f0;"></button></i></a>
  </div>
  <?php } ?>
</section>
</body>
</html>