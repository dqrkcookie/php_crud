<?php
	include_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

.record {
    font-weight: bold;
}

tr:hover {
    background-color: #f5f5f5;
}
    </style>
</head>
<body>
    <h1>Product List</h1>
    <table>
        <thead>
            <tr>
            <td class="record">Full Name</td>
				<td class="record">Address</td>
				<td class="record">Mobile</td>
				<td class="record">E-mail</td>
				<td class="record">Birthday</td>
				<td class="record">Picture</td>
				<td class="record">Username</td>
				<td class="record">Password</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $conn->prepare("SELECT * FROM product_tbl");
            $query->execute();

            while ($data = $query->fetch()) {
            
                $fullname = $data['fullname'];
                $Address = $data['Address'];
                $Mobile = $data['Mobile'];
                $Email = $data['Email'];
                $Birthday = $data['Birthday'];
                $picture = $data['productPicture'];
                $Username = $data['Username'];
                $password = $data['password'];
            ?>
            <tr>
            <td class="records"><?php echo $fullname;?></td>
				<td class="records"><?php echo $Address;?></td>
				<td class="records"><?php echo $Mobile;?></td>
				<td class="records"><?php echo $Email;?></td>
				<td class="records"><?php echo $Birthday;?></td>
				<td class="records"><img src="productPictures/<?php echo $picture;?>" alt="Picture" width="100"></td>
				<td class="records"><?php echo $Username;?></td>
				<td class="records"><?php echo $password;?></td>
				
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>