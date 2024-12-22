<?php

include_once("../config/connect.php");
session_start();

if(empty($_SESSION['username'])){
  header("Location: ../index.php");
}

$username = $_SESSION['username'];

try{
  $stmt = $pdo->query('SELECT * FROM product_tbl ORDER BY productID');
  $query2 = "SELECT * FROM shapi_cart WHERE username = ?";
  $query3 = "SELECT * FROM placed_order WHERE username = ?";
  $query4 = "SELECT * FROM users_tbl WHERE username = ?";
  $query5 = "SELECT * FROM accepted_orders WHERE username = ? AND accept = ?";
  $query6 = "SELECT * FROM transaction_history WHERE name = ?";
  $query7 = "SELECT * FROM pending_orders WHERE username = ?";
  $accept = 'yes';

  $stmt2 = $pdo->prepare($query2);
  $stmt3 = $pdo->prepare($query3);
  $stmt4 = $pdo->prepare($query4);
  $stmt5 = $pdo->prepare($query5);
  $stmt6 = $pdo->prepare($query6);
  $stmt7 = $pdo->prepare($query7);

  $stmt2->bindParam(1, $username);
  $stmt3->bindParam(1, $username);
  $stmt4->bindParam(1, $username);
  $stmt5->bindParam(1, $username);
  $stmt5->bindParam(2, $accept);
  $stmt6->bindParam(1, $username);
  $stmt7->bindParam(1, $username);

  $stmt2->execute();
  $stmt3->execute();
  $stmt4->execute();
  $stmt5->execute();
  $stmt6->execute();
  $stmt7->execute();
}catch(PDOException $e){
  error_log('Database error:' . $e->getMessage());
}

$cartItems = $stmt2->fetchAll();

$rows = 0;
$price = 0;
foreach($cartItems as $item){
  $rows++;
  $price += ($item->price * $item->quantity);
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
  <?php $id = uniqid() ?>

  <!-- NAV -->
  <nav>
    <ul>
      <li><a href="../src/pages/main.php">Shapi</a></li>
      <li>
        <form action="../remote/searchproduct.php" method="GET">
          <input type="text" placeholder="Search product" name="search" required>
          <button type="submit" name="look" value="products">
            <i class="fa-solid fa-sm fa-magnifying-glass" style="color: #f0f0f0;"></i>
          </button>
        </form>
      </li>
      <div>
        <div>
          <li><span id="items"><?php if($rows !== 0) {echo $rows;} ?></span><button popovertarget="cart" id="cart_btn">
            <i class="fa-solid fa-cart-shopping fa-xl" style="color: #5b6edb;"></i>
          </button></li>
          <li><button id="place" popovertarget="orders">My Orders 
            <i class="fa-solid fa-bag-shopping" style="color: #f0f0f0;"></i>
          </button></li>
          <li><button id="place" popovertarget="history">
            <i class="fa-solid fa-clock" style="color: #f0f0f0;"></i>
          </button></li>
          <li><button id="place" class="account" popovertarget="profile">Account 
            <i class="fa-solid fa-user fa-sm" style="color: #f0f0f0;"></i>
          </button></li>
        </div>
        <li>
          <form action="../remote/logout.php" method="POST">
            <button type="submit" name="logout">Log out</button>
          </form>
        </li>
        <li id="bar">
          <i class="fa-solid fa-bars fa-xl" style="color: #5b6edb;"></i>
        </li>
      </div>
    </ul>
  </nav>

  <!-- SHOPPING CART -->
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
        <?php foreach($cartItems as $data) { ?>
          <?php $rows++?>
        <tr>
          <td><?php echo $data->name ?></td>
          <td><?php echo $data->quantity ?></td>
          <td>₱<?php echo number_format($data->price, 2) ?></td>
          <td>
            <a href="../remote/deletefromcart.php?num=<?php echo $data->num ?>">
              <button class="btn">Delete</button>
            </a>
          </td>
        </tr>
        <?php } ?>
        <?php $stmt2->execute(); ?>
        <?php $data = $stmt2->fetchALL(); ?>
        <?php if(!empty($data)) { ?>
        <tr>
          <td class="checkout"><button popovertarget="sure">Checkout now</button></td>
          <td>Total</td>
          <td class="total-price">₱<?php echo number_format($price, 2) ?></td>
          <td></td>
        </tr>
        <?php } else { ?> 
          <tr> 
            <td colspan="4"> 
              <p>Cart is empty. Start shopping now!</p> 
          </td> 
        </tr> <?php } ?>
      </tbody>
    </table>
  </div>
  
  <!-- ORDER CONFIRMATION -->
  <div id="sure" popover>
    <span>Confirm your order</span>
    <span>Address: <?php echo $user->address ?></span>
    <span>MOP: Cash on Delivery</span>
    <div>
      <td class="checkout">
        <a href="../remote/checkout.php?checkout=true&username=<?php echo $username ?>&address=<?php echo $user->address ?>&payment=<?php echo $price ?>&total=<?php echo number_format($price, 2) ?>&id=<?php echo $id ?>">
          Yes
        </a>
      </td>
      <button id="no" onclick="hide()">No</button>
    </div>
  </div>
  
  <!-- PLACED ORDERS -->
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
        <?php $items = []; ?>
        <?php while($data = $stmt3->fetch()) { ?>
          <?php array_push($items , $data->name) ?>
        <tr>
          <td><?php echo $data->name ?></td>
          <td><?php echo $data->quantity ?></td>
          <td><?php echo number_format($data->price, 2) ?></td>
        </tr>
        <?php } ?>
        <?php $stmt3->execute(); ?>
        <?php $stmt5->execute(); ?>
        <?php $stmt7->execute(); ?>
        <?php $data = $stmt5->fetch(); ?>
        <?php $data_p = $stmt7->fetch(); ?>
        <?php $data_f = $stmt3->fetchALL(); ?>
        <?php $user2 = ''; foreach($data_f as $user1){$user2 = $user1->username;} if(!empty($user2) && !empty($data_p)){ ?> 
          <tr> 
              <td colspan="4"> 
                <p>Please wait for Shapi's confirmation. Thank you!</p> 
            </td> 
          </tr>
          <?php } ?>
          <?php 
            $notif_query = "SELECT * FROM order_notifications WHERE username = ? AND is_read = FALSE";
            $notif_stmt = $pdo->prepare($notif_query);
            $notif_stmt->execute([$username]);
            $notification = $notif_stmt->fetch();      
            
            if($notification) { ?>
            <?php $decodeJson = json_decode($notification->items); ?>
            <tr>
                <td>*</td>
                <td>Your order <span id="items"><?php echo implode('pcs & ', $decodeJson)?>pcs</span> 
                    will arrive soon! Prepare amount 
                    <span class="total-price">₱<?php echo $notification->amount; ?></span>
                    for payment
                </td>
                <td>*</td>
            </tr>
          <tr>
            <td></td>
            <?php 
            $toJson = json_encode($items);
            $toUrl = urlencode($toJson);
            ?>
            <td>
              <a href="../remote/orderresponse.php?id=<?php echo $notification->id ?>&name=<?php echo $username ?>&amount=<?php echo $notification->amount ?>&status=success&isRead=<?php echo true ?>&item=<?php echo $toUrl ?>">
                <button class="status_btn">Received</button>
              </a>
              <a href="../remote/orderresponse.php?id=<?php echo $notification->id ?>&name=<?php echo $username ?>&amount=<?php echo $notification->amount ?>&status=failed&isRead=<?php echo true ?>&item=<?php echo $toUrl ?>">
                <button class="status_btn">Return</button>
              </a>
            </td>
            <td></td>
          </tr>
          <?php } else if(empty($user2)){ ?>
            <tr> 
                <td colspan="4"> 
                  <p>No pending orders. Checkout now!</p> 
              </td> 
            </tr>
          <?php } ?>
      </tbody>
    </table>
  </div>
  
  <!-- PURCHASE HISTORY -->
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
        <?php 
        $data = $stmt6->fetchAll(); 
        if (empty($data)) { ?> 
        <tr> 
          <td colspan="4"> 
            <p>No history. Browse products now!</p> 
          </td> 
        </tr> 
        <?php } else { foreach ($data as $item) { ?> 
          <tr> 
              <td><?php echo $item->name; ?></td> 
              <td>₱<?php echo $item->amount; ?></td> 
              <td><?php if ($item->status == 'success') { echo    $item->transactionDate; } else { echo $item->status; } ?></td> 
          </tr> <?php } } ?>
      </tbody>
    </table>
  </div>

  <!-- PROFILE SETTINGS -->
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

    <a href="#" class="edit-btn">
      <button popovertarget="edit-profile" id="eProfile">Edit Profile</button>
    </a>
  </div>

  <div class="edit-profile-container" popover id="edit-profile">
    <form action="../remote/editprofile.php" method="POST" enctype="multipart/form-data">
      <div class="profile-picture">
        <img src="../src/images/profile_picture/<?php echo $user->profile_picture ?>" alt="Profile Picture">
        <input type="file" name="profile" accept="image/*">
      </div>

      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?php echo $user->name ?>" required>
      </div>

      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?php echo $user->address ?>" required>
      </div>

      <div class="form-group">
        <label for="birthday">Birthday</label>
        <input type="date" id="birthday" name="birthday" value="<?php echo $user->birthday ?>" required>
      </div>

      <div class="form-group">
        <label for="mobile">Mobile Number</label>
        <input type="tel" id="mobile" name="mobile" value="<?php echo $user->mobile ?>" required>
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="<?php echo $user->email ?>" required>
      </div>

      <button type="submit" class="save-btn" name="save">Save Profile</button>
    </form>
  </div>

  <!-- DISPLAYING PRODUCTS -->
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
          <?php if($d->productStocks == 'Available') { ?>
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
                <span class="qty">qty:<input type="number" value="1" name="qty" id="qty" min="1"></span>
              </form>
            </div>
          <?php } ?>
        <?php } ?>
      </section>
    </div>   
  <script src="../pages/js/index.js"></script>
</body>
</html>


















