<?php 

include("./nav.php");

try {
    $stmt = $pdo->query('SELECT * FROM accepted_orders');
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
}

?>

<section>
    <div class="orders-container">
        <div class="order-header">
            <h1>Accepted Orders</h1>
        </div>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Total Items</th>
                    <th>Total Price</th>
                    <th>Delivery Address</th>
                    <th>To be Delivered</th>
                </tr>
            </thead>
            <tbody>
                <?php if($stmt->rowCount() > 0) { ?>
                <?php while ($data = $stmt->fetch()) { ?>
                    <tr>
                        <td><?php echo $data->id; ?></td>
                        <td><?php echo $data->username; ?></td>
                        <td><?php echo $data->numberOfItems; ?></td>
                        <td><?php echo $data->payment; ?></td>
                        <td><?php echo $data->address; ?></td>
                        <td><?php echo $data->accept; ?></td>
                    </tr>
                    <?php } ?>
                <?php } else { ?>
                            <tr>
                                <td colspan="6">No accepted orders</td>
                            </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
</section>
