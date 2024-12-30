<?php

include_once('../../config/connect.php');
session_start();
if (empty($_SESSION['admin'])) {
    header("Location: ../../index.php");
}

try {
    $stmt = $pdo->query('SELECT * FROM product_tbl ORDER BY productID');
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shapi Dashboard</title>
    <link rel="stylesheet" href="../style/home.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="./admin.php"><button class="add-item-btn">Go back</button></a>
            <div class="logo">
                <a href="./addproduct.php">Add Product</a>
            </div>
            <div class="nav-buttons">
                <button popovertarget="add_product" class="add-item-btn">+ Add Product</button>
            </div>
        </div>
    </nav>

    <div id="add_product" popover>
        <h1 style="text-align: center; color: black;">Add Product</h1>
        <form action="../../remote/addproduct.php" method="POST" enctype="multipart/form-data">
            <input type="text" placeholder="Product Name" name="product_name" required>
            <input type="text" placeholder="Category" name="category" required>
            <textarea placeholder="Product Details" name="product_details" required></textarea>
            <input type="text" placeholder="Price" name="product_price" required>
            <input type="file" name="picture" required accept="image/*">
            <label for="show">Display: </label>
            <select name="show" id="show" required>
                <option value="">Select</option>
                <option value="Normal">Normal</option>
                <option value="Slider">Slider</option>
            </select>
            <br>
            <label for="stock">Stock: </label>
            <select name="product_stocks" id="stock" required>
                <option value="">Select</option>
                <option value="Available">In Stock</option>
                <option value="Sold Out">Out of Stock</option>
            </select>
            <input type="submit" value="Add Product" name="add_product">
        </form>
    </div>

    <div class="list_container">
        <table>
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Picture</td>
                    <td>Details</td>
                    <td>Price</td>
                    <td>Stocks</td>
                    <td>Category</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php while ($data = $stmt->fetch()) { ?>
                    <tr>
                        <td><?php echo $data->productName; ?></td>
                        <td id="img"><img src="../images/<?php echo $data->productPicture; ?>" alt="Product Image" class="product-img"></td>
                        <td><?php echo $data->productDetails; ?></td>
                        <td><?php echo '₱' . $data->productPrice; ?></td>
                        <td><?php echo $data->productStocks; ?></td>
                        <td><?php echo $data->category; ?></td>
                        <td>
                            <ul id="actions">
                                <li><button class="btn" popovertarget="view_product-<?php echo $data->productID; ?>">View</button></li>
                                <li><button class="btn" popovertarget="edit_product-<?php echo $data->productID; ?>" id="edit">Edit</button></li>
                                <li><a href="../../remote/delete.php?id=<?php echo $data->productID; ?>"><button class="btn">Delete</button></a></li>
                            </ul>
                        </td>
                    </tr>

                    <div class="view_product" id="view_product-<?php echo $data->productID; ?>" popover>
                        <h1>About the product</h1>
                        <span>Name: <?php echo $data->productName; ?></span>
                        <img src="../images/<?php echo $data->productPicture; ?>" alt="Product Image">
                        <span>Details: <?php echo $data->productDetails; ?></span>
                        <span>Price: <?php echo $data->productPrice; ?></span>
                        <span>Stocks: <?php echo $data->productStocks; ?></span>
                    </div>

                    <div class="edit_product" id="edit_product-<?php echo $data->productID; ?>" popover>
                        <h1>Edit product</h1>
                        <form action="../../remote/edit.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="productID" value="<?php echo $data->productID; ?>">
                            <label>Name</label>
                            <input type="text" name="name" value="<?php echo $data->productName; ?>">
                            <label>Category</label>
                            <input type="text" name="category" value="<?php echo $data->category ?>">
                            <label>Picture</label>
                            <input type="file" name="picture">
                            <label>Details</label>
                            <textarea name="details"><?php echo $data->productDetails; ?></textarea>
                            <label>Price</label>
                            <input type="text" name="price" value="<?php echo $data->productPrice; ?>">
                            <label for="show">Display: </label>
                            <select name="show" id="show" required>
                                <option value="">Select</option>
                                <option value="Normal">Normal</option>
                                <option value="Slider">Slider</option>
                            </select>
                            <br>
                            <label for="stock">Stock: </label>
                            <select name="stock" id="stock" required>
                                <option value="">Select</option>
                                <option value="Available">In Stock</option>
                                <option value="Sold Out">Out of Stock</option>
                            </select>
                            <input type="submit" value="Save changes" name="edit_btn" id="edit_btn">
                        </form>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
