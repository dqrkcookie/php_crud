<?php 

include("./nav.php"); 

try {
    $stmt = $pdo->query('SELECT * FROM product_tbl');
    $stmt2 = $pdo->query('SELECT * FROM transaction_history');
    $stmt3 = $pdo->query('SELECT * FROM users_tbl');

    $data = $stmt->fetchAll();
    $data1 = $stmt2->fetchAll();
    $data2 = $stmt3->fetchAll();
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
}

?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Welcome to Shapi admin dashboard.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h2>Total Products</h2>
            <?php 
            $rows = count($data);
            ?>
            <p><?php echo $rows; ?></p>
        </div>
        <div class="stat-card">
            <h2>Total Orders</h2>
            <?php 
            $rows = count($data1);
            ?>
            <p><?php echo $rows; ?></p>
        </div>
        <div class="stat-card">
            <h2>Total Users</h2>
            <?php 
            $rows = count($data2);
            ?>
            <p><?php echo $rows; ?></p>
        </div>
        <div class="stat-card revenue">
            <h2>Revenue</h2>
            <?php 
            $revenue = 0;
            foreach ($data1 as $d) {
                if ($d->status == 'success') {
                    $revenue += $d->amount;
                }
            }
            ?>
            <p>₱ <?php echo number_format($revenue, 2); ?></p>
        </div>
    </div>
    <div id="chart_div">
        <img src="../images/icon/revenue.png" alt="Chart" id="chart">
    </div>
</div>
