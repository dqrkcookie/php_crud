<?php include("./nav.php"); 

try{
  $stmt = $pdo->query("SELECT * FROM transaction_history");
}catch(PDOException $e){
  error_log('Database error:' . $e->getMessage());
}

?>

<section>
    <div class="orders-container">
      <div class="order-header">
        <h1>Transaction History</h1>
      </div>

      <table class="order-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if($stmt->rowCount() > 0) { ?>
          <?php while($data = $stmt->fetch()) {?>
          <tr>  
            <td><?php echo $data->orderID ?></td>
            <td><?php echo $data->name ?></td>
            <td><?php echo $data->amount ?></td>
            <td><?php echo $data->transactionDate ?></td>
            <td><?php echo $data->status ?></td>
          <?php } ?>
          <?php } else { ?>
                  <tr>
                      <td colspan="5">No transaction history</td>
                  </tr>
          <?php } ?>
        </tbody>
      </table>
  </div>
  </section>