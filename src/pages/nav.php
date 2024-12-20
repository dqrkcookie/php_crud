<?php 

include_once("../../config/connect.php");
session_start();

if (empty($_SESSION['admin'])) {
    header("Location: ../../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../style/admin.css">
</head>
<body>
    <nav>
        <ul>
            <li id="storeName">
                <a id="shapi" href="./admin.php">Shapi</a>
            </li>
            <li>
                <a href="./admin.php">Home</a>
            </li>
            <li>
                <a href="./addproduct.php">Add Product to Store</a>
            </li>
            <li>
                <a href="./allorders.php">Pending Orders</a>
            </li>
            <li>
                <a href="./accepted.php">Accepted Orders</a>
            </li>
            <li>
                <a href="./transaction.php">Transaction History</a>
            </li>
            <li>
                <a href="./users.php">User List</a>
            </li>
            <li>
                <div class="nav-buttons">
                    <form action="../../remote/logout.php" method="POST">
                        <button type="submit" class="add-item-btn" name="adminLogout">Log Out</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
</body>
</html>
