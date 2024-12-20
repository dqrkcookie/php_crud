<?php 

include("./nav.php");

try{
  $stmt = $pdo->query('SELECT * FROM pending_orders');
  $stmt1 = $pdo->prepare('SELECT * FROM placed_order WHERE uniqOrderId = ?');
}catch(PDOException $e){
  error_log('Database error: ' . $e->getMessage());
}

?>

<section>
    <div class="orders-container">
      <div class="order-header">
        <h1>Pending Orders</h1>
      </div>

      <table class="order-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>User Name</th>
            <th>Total Items</th>
            <th>Total Price</th>
            <th>Delivery Address</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($data = $stmt->fetch()) {?>
            <?php 
              $stmt1->bindParam(1, $data->orderId); 
              $stmt1->execute();
              $orderDetails = $stmt1->fetchALL();

              $listOfItems = [];
              foreach($orderDetails as $item){
                array_push($listOfItems, $item->name);
              }

              $toJson = json_encode($listOfItems);
              $toUrl = urlencode($toJson);
              ?>
          <tr>  
            <td><?php echo $data->id ?></td>
            <td><?php echo $data->username ?></td>
            <td><?php echo $data->numberOfItems ?></td>
            <td>₱<?php echo $data->payment ?></td>
            <td><?php echo $data->address ?></td>
            <td>
              <div class="order-actions">
                <a href="../../remote/acceptorder.php?accept=yes&orderid=<?php echo $data->id ?>&username=<?php echo $data->username ?>&payment=<?php echo $data->payment ?>&list=<?php echo $toUrl ?>"><button class="btn-accept">Accept Now</button></a>
                <button class="btn-reject">Reject</button>
              </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
  </div>
  </section>