<?php 

include("./nav.php");

try{
  $stmt = $pdo->query('SELECT * FROM pending_orders');
  $stmt1 = $pdo->prepare('SELECT * FROM pending_orders WHERE orderId = ?');
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
          <?php if($stmt->rowCount() > 0) { ?>
          <?php while($data = $stmt->fetch()) {?>
            <?php 
              $stmt1->bindParam(1, $data->orderId); 
              $stmt1->execute();
              $items = $stmt1->fetch();
              $toUrl = urlencode($items->ListOfItems);
              $listItems = json_decode($items->ListOfItems);
              ?>
          <tr>  
            <td><?php echo $data->id ?></td>
            <td><?php echo $data->username ?></td>
            <td><?php foreach($listItems as $list) { echo $list . 'pcs' . ' '; } ?></td>
            <td>₱<?php echo $data->payment ?></td>
            <td><?php echo $data->address ?></td>
            <td>
              <div class="order-actions">
                <a href="../../remote/acceptorder.php?accept=yes&orderid=<?php echo $data->orderId ?>&username=<?php echo $data->username ?>&payment=<?php echo $data->payment ?>&list=<?php echo $toUrl ?>"><button class="btn-accept">Accept Now</button></a>
                <button class="btn-reject">Reject</button>
              </div>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
                  <tr>
                      <td colspan="6">No pending orders</td>
                  </tr>
          <?php } ?>
        </tbody>
      </table>
  </div>
  </section>