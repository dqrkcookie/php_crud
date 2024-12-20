<?php

include_once("../config/connect.php");
session_start();

if(empty($_SESSION['username'])){
  header("Location: ../index.php");
}

try{
    $username = $_SESSION['username'];

    $stmt = $pdo->query('SELECT * FROM product_tbl ORDER BY productID');
    $query2 = "SELECT * FROM shapi_cart WHERE username = ?";
    $query3 = "SELECT * FROM placed_order WHERE username = ?";
    $query4 = "SELECT * FROM users_tbl WHERE username = ?";
    $query5 = "SELECT * FROM accepted_orders WHERE username = ? ORDER BY id DESC LIMIT 1";
    $query6 = "SELECT * FROM transaction_history WHERE name = ?";
    
    $stmt2 = $pdo->prepare($query2);
    $stmt3 = $pdo->prepare($query3);
    $stmt4 = $pdo->prepare($query4);
    $stmt5 = $pdo->prepare($query5);
    $stmt6 = $pdo->prepare($query6);
    $stmt2->bindParam(1, $username);
    $stmt3->bindParam(1, $username);
    $stmt4->bindParam(1, $username);
    $stmt5->bindParam(1, $username);
    $stmt6->bindParam(1, $username);
    
    $stmt2->execute();
    $stmt3->execute();
    $stmt4->execute();
    $stmt5->execute();
    $stmt6->execute();
} catch (PDOException $e){
    error_log('Database error:' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shapi</title>
  <link rel="stylesheet" href="../src/style/main.css">
  <script src="https://kit.fontawesome.com/b70669fb91.js" crossorigin="anonymous"></script>
</head>
<body>
<?php $user = $stmt4->fetch(); ?>
<nav>
  <ul>
    <li><a href="./main.php">Shapi</a></li>
    <li
    >
    <form action="./searchproduct.php" method="GET">
      <input type="text" placeholder="Search product" name="search" required>
      <button type="submit" name="look" value="products"><i class="fa-solid fa-sm fa-magnifying-glass" style="color: #f0f0f0;"></i></button>
    </form>
    </li>
    <div>
      <div>
        <li><button popovertarget="cart" id="cart_btn"><i class="fa-solid fa-cart-shopping fa-xl" style="color: #5b6edb;"></i></button></li>
        <li><button id="place" popovertarget="orders">My Orders <i class="fa-solid fa-bag-shopping" style="color: #f0f0f0;"></i></button></li>
        <li><button id="place" popovertarget="history"><i class="fa-solid fa-clock" style="color: #f0f0f0;"></i></button></li>
        <li><button id="place" class="account" popovertarget="profile">Account <i class="fa-solid fa-user fa-sm" style="color: #f0f0f0;"></i></i></button></li>
      </div>
      <li>
        <form action="./logout.php" method="POST">
          <button type="submit" name="logout">Log out</button>
        </form>
      </li>
      <li id="bar"><i class="fa-solid fa-bars fa-xl" style="color: #5b6edb;"></i>
      </li>
    </div>
  </ul>
</nav>

<div class="cart" id="cart" popover>
  <table>
    <thead>
      <tr>
        <td>Name</td>
        <td>Quantity</td>
        <td>Price</td>
        <td>Action</td>
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
          <td>₱<?php echo $data->price ?></td>
          <td><a href="./remote/deletefromcart.php?num=<?php echo $data->num ?>"><button class="btn">Delete</button></a>
        </td>
        </tr>
      <?php } ?>
      <?php $stmt2->execute() ?>
      <?php $data = $stmt2->fetchALL() ?>
      <?php if(!empty($data)) { ?>
      <?php $price = 0; foreach($data as $d) {$price += ($d->price*$d->quantity);} ?>
        <tr>
          <td class="checkout"><a href="./remote/checkout.php?checkout=true&username=<?php echo $username ?>&address=<?php echo $user->address ?>&payment=<?php echo $price ?>">Checkout now</a></td>
          <td>Total</td>
          <td class="total-price">₱<?php echo $price?></td>
          <td></td>
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
        <h2 class="cartTitle">Pending Orders</h2>
      </tr> 
      <?php while($data = $stmt3->fetch()) { ?>
        <tr>
          <td><?php echo $data->name ?></td>
          <td><?php echo $data->quantity ?></td>
          <td><?php echo $data->price ?></td>
        </tr>
      <?php } ?>
      <?php $stmt3->execute() ?>
      <?php $stmt5->execute() ?>
      <?php $data = $stmt5->fetch() ?>
      <?php $data_f = $stmt3->fetchALL() ?>
      <?php if(!empty($data)) {?>
        <?php if($data->accept == 'yes') {?>
        <tr>
          <td>*</td>
          <td>Your order is on the way! Prepare amount <span class="total-price">₱<?php $price = 0; foreach($data_f as $d) {$price += ($d->price*$d->quantity);}
          echo $price?></span> for payment</td>
          <td>*</td>
        </tr>
        <tr>
          <td></td>
          <td><a href="./remote/orderresponse.php?id=<?php echo $data->id ?>&name=<?php echo $data->username ?>&amount=<?php echo $data->payment ?>&status=success"><button class="status_btn">Received</button></a>
          <a href="./remote/orderresponse.php?id=<?php echo $data->id ?>&name=<?php echo $data->username ?>&amount=<?php echo $data->payment ?>&status=failed"><button class="status_btn">Return</button></td></a>
          <td></td>
        </tr>
        <?php } ?>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="cart" id="history" popover>
  <table>
    <thead>
      <tr>
        <td>Name</td>
        <td>Amount</td>
        <td>Date delivered</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <h2 class="cartTitle">Purchase History</h2>
      </tr>                                                     
      <?php while($data = $stmt6->fetch()) { ?>
        <tr>
          <td><?php echo $data->name ?></td>
          <td>₱<?php echo $data->amount ?></td>
          <td><?php if($data->status == 'success'){echo $data->transactionDate;}else{echo $data->status;} ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<div class="profile-container" popover id="profile">
    <div class="profile-picture">
        <img src="../src/images/profile_picture/<?php echo $user->profile_picture ?>" alt="Profile Picture">
    </div>

    <div class="profile-info">
        <div class="label">Full Name:</div>
        <div class="value"><?php echo $user->name ?></div>

        <div class="label">Address:</div>
        <div class="value"><?php echo $user->address ?></div>

        <div class="label">Birthday:</div>
        <div class="value"><?php echo $user->birthday ?></div>

        <div class="label">Mobile Number:</div>
        <div class="value"><?php echo $user->mobile ?></div>

        <div class="label">Email Address:</div>
        <div class="value"><?php echo $user->email ?></div>
    </div>

    <a href="#" class="edit-btn"><button popovertarget="edit-profile" id="eProfile">Edit Profile</button></a>
</div>
  
<div class="edit-profile-container" popover id="edit-profile">
    <form action="./remote/editprofile.php" method="POST" enctype="multipart/form-data">
        <div class="profile-picture">
            <img src="../src/images/profile_picture/<?php echo $user->profile_picture ?>" alt="Profile Picture">
            <input type="file" name="profile" accept="image/*">
        </div>

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" required>
        </div>

        <div class="form-group">
            <label for="birthday">Birthday</label>
            <input type="date" id="birthday" name="birthday" required>
        </div>

        <div class="form-group">
            <label for="mobile">Mobile Number</label>
            <input type="tel" id="mobile" name="mobile" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>

        <button type="submit" class="save-btn" name="save">Save Profile</button>
    </form>
</div>

<?php

if(isset($_GET['look'])){
  $search = $_GET['search'];
  $concatenateSearch = '%' . $search . '%';

   $query = "SELECT * FROM product_tbl WHERE productName LIKE ?";
   $stmt = $pdo->prepare($query);
   $stmt->bindParam(1, $concatenateSearch);
   $stmt->execute();

   $data = $stmt->fetchALL();
}

?>

<div id="s_back"><a href="../src/pages/main.php"><button id="place">Go back</button></a>
  </div>
  <div class="for_sec">
    <section>
      <?php foreach($data as $d) { ?>
      <div class="item">
        <h1><?php echo $d->productName ?></h1>
        <img src="../src/images/<?php echo $d->productPicture ?>"></img>
        <span id="d">See details..</span>
        <span id="details"><?php echo $d->productDetails ?></span>
        <span id="price">Price: ₱<?php echo $d->productPrice ?></span>
        <form action="./addtocart.php" method="GET" class="cart-qty">
          <input type="hidden" name="name" value="<?php echo $d->productName ?>">
          <input type="hidden" name="price" value="<?php echo $d->productPrice ?>">
          <input type="hidden" name="username" value="<?php echo $username ?>">
          <button type="submit">Add to cart <i class="fa-solid fa-cart-shopping fa-sm" style="color: #f0f0f0;"></i></button>
          <span class="qty">qty:<input type="number" value="1" name="qty" id="qty"></span>
        </form>
      </div>
      <?php } ?>
    </section>
  </div>
</body>
</html>