<?php

include_once("../../config/connect.php");
session_start();

if(empty($_SESSION['username'])){
  header("Location: ./login.php");
}

$username = $_SESSION['username'];

try{
  $product = $pdo->query('SELECT * FROM product_tbl ORDER BY productID');
  $query2 = "SELECT * FROM shapi_cart WHERE username = ?";
  $query3 = "SELECT * FROM placed_order WHERE username = ?";
  $query4 = "SELECT * FROM users_tbl WHERE username = ?";
  $query5 = "SELECT * FROM accepted_orders WHERE username = ? AND accept = ?";
  $query6 = "SELECT * FROM transaction_history WHERE name = ? ORDER BY transactionDate";
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

  $data = $product->fetchAll();
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

$categories = [];
foreach($data as $d){
  if(!in_array($d->category, $categories)){
    array_push($categories, $d->category);
  }
}
  
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
  <?php $user = $stmt4->fetch(); ?>
  <?php $id = uniqid() ?>

  <!-- NAV -->
  <nav>
    <ul>
      <li><a href="./main.php">Shapi</a></li>
      <li>
        <form action="../../remote/searchproduct.php" method="GET">
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
            <i class="fa-solid fa-bag-shopping" style="color: #f0f0f0;"></i> <?php echo $stmt3->rowCount(); ?>
          </button></li>
          <li><button id="place" popovertarget="history">
            <i class="fa-solid fa-clock" style="color: #f0f0f0;"></i>
          </button></li>
          <li><button id="place" class="account" popovertarget="profile">Account 
            <i class="fa-solid fa-user fa-sm" style="color: #f0f0f0;"></i>
          </button></li>
        </div>
        <li>
          <form action="../../remote/logout.php" method="POST">
            <button type="submit" name="logout">Log out</button>
          </form>
        </li>
        <li id="bar">
          <i class="fa-solid fa-bars fa-xl" style="color: #5b6edb;"></i>
        </li>
      </div>
    </ul>
  </nav>

  <div class="dropdown">
      <button class="dropdown-btn">Categories ▾</button>
      <div class="dropdown-content">
        <a href="./main.php">All</a>
        <?php foreach($categories as $c) { ?>
          <a href="./category.php?category=<?php echo $c ?>"><?php echo $c ?></a>
          <?php } ?>
      </div>
  </div>

  <div class="sort-group dropdown">
      <form action="./main.php" method="GET" class="sort-form">
          <label for="sort">Sort by:</label>
          <select id="sort" name="sort">
              <option value="a-z">Product name: A-Z</option>
              <option value="z-a">Product name: Z-A</option>
              <option value="price-low">Product price: Low to High</option>
              <option value="price-high">Product price: High to Low</option>
          </select>
          <input type="submit" value="✔" name="submit-sort">
      </form>
  </div>

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
            <a href="../../remote/deletefromcart.php?num=<?php echo $data->num ?>">
              <button class="btn">Remove</button>
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
        <a href="../../remote/checkout.php?checkout=true&username=<?php echo $username ?>&address=<?php echo $user->address ?>&payment=<?php echo $price ?>&total=<?php echo number_format($price, 2) ?>&id=<?php echo $id ?>">
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
              <a href="../../remote/orderresponse.php?id=<?php echo $notification->order_id ?>&name=<?php echo $username ?>&amount=<?php echo $notification->amount ?>&status=success&isRead=<?php echo true ?>&item=<?php echo $toUrl ?>">
                <button class="status_btn">Received</button>
              </a>
              <a href="../../remote/orderresponse.php?id=<?php echo $notification->order_id ?>&name=<?php echo $username ?>&amount=<?php echo $notification->amount ?>&status=failed&isRead=<?php echo true ?>&item=<?php echo $toUrl ?>">
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
          <td>Report</td>
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
              <td><?php echo $item->name ?></td> 
              <td>₱<?php echo $item->amount ?></td> 
              <td><?php if ($item->status == 'success') { echo    $item->transactionDate; } else { echo $item->status; } ?></td> 
              <td><?php echo $item->status ?></td>
          </tr> <?php } } ?>
      </tbody>
    </table>
  </div>

  <!-- PROFILE SETTINGS -->
  <div class="profile-container" popover id="profile">
    <div class="profile-picture">
      <img src="../images/profile_picture/<?php echo $user->profile_picture ?>" alt="Profile Picture">
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
    <form action="../../remote/editprofile.php" method="POST" enctype="multipart/form-data">
      <div class="profile-picture">
        <img src="../images/profile_picture/<?php echo $user->profile_picture ?>" alt="Profile Picture" required>
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
  
  $category = "SELECT * FROM product_tbl WHERE category = ?";
  $stmt = $pdo->prepare($category);
  if(isset($_GET['category'])){
    $stmt->bindParam(1, $_GET['category']);
    $stmt->execute();
    $products = $stmt->fetchALL();
  }
  ?>

  <div class="for_sec">
    <section>
      <?php foreach($products as $data) { ?>
        <?php if($data->productStocks == 'Available' && $data->totalStocks > 0) { ?>
          <div class="item">
            <h1><?php echo $data->productName ?></h1>
            <img src="../images/<?php echo $data->productPicture ?>"></img>
            <span id="d">See details..</span>
            <span id="details"><?php echo $data->productDetails ?></span>
            <span id="price">Price: ₱<?php echo number_format($data->productPrice, 2) ?></span>
            <form action="../../remote/addtocart.php" method="GET" class="cart-qty">
              <input type="hidden" name="name" value="<?php echo $data->productName ?>">
              <input type="hidden" name="price" value="<?php echo $data->productPrice ?>">
              <input type="hidden" name="username" value="<?php echo $username ?>">
              <button type="submit">Add to cart 
                <i class="fa-solid fa-cart-shopping fa-sm" style="color: #f0f0f0;"></i>
              </button>
              <span class="qty">qty:
                <input type="number" value="1" name="qty" id="qty" min="1">
              </span>
            </form>
            <?php if($d->totalStocks < 30) { ?>
              <p><?php echo $d->totalStocks ?> stock(s) remaining</p>  
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>
    </section>
  </div>

  <script>
    function hide() {
      const sure = document.getElementById('sure');

      sure.style.display = 'none';

      window.location.href = '../pages/main.php';
    }

    function category() {
      const dropdownBtn = document.querySelector('.dropdown-btn');
        const dropdownContent = document.querySelector('.dropdown-content');

        dropdownBtn.addEventListener('click', (e) => {
            dropdownContent.classList.toggle('show');
            e.stopPropagation();
        });

        window.addEventListener('click', () => {
            if (dropdownContent.classList.contains('show')) {
                dropdownContent.classList.remove('show');
            }
        });
    }
    category();
  </script>
</body>
</html>
