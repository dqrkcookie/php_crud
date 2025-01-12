<?php
	include_once("../../config/connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shapi</title>
    <link rel="stylesheet" href="../style/style.css" >
</head>
<body>
    <div id="admin">
        <button id="admin_btn">Continue as Admin</button>
    </div>
    <!-- // LOGIN -->
    <div class="login">
        <form action="../../remote/login.php" method="POST">
            <h1> 
                Log In 
            </h1>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="login" value="Log in">
            <p>No account yet? <span id="go_signup">Sign Up</span></p>
        </form>
    </div>
    <!-- LOGIN AS ADMIN -->
    <div id="loginAdmin">
        <form action="./remote/login.php" method="POST">
            <h1> 
                Admin Log In 
            </h1>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="loginAdmin" value="Log in">
        </form>
    </div>
    <!-- // REGISTER -->
    <div class="signup">
        <form action="../../remote/signup.php" method="POST" enctype="multipart/form-data">    
        <span id="go_back">Go back</span>
            <h1> 
                Sign Up 
            </h1>
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="name" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" required>
            <label for="number">Mobile Number:</label>
            <input type="text" id="number" name="number" required>
            <label for="email">Email Address:</label>
            <input type="text" id="email" name="email" required>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required">
            <label for="picture">Profile Picture:</label>
            <input type="file" id="picture" name="picture" required accept="image/*">
            <input type="submit" name="signup" value="Sign Up">
        </form>
    </div>
    <script src="../js/index.js"></script>
</body>
</html>