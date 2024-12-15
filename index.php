<?php
	include_once("./config/connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shapi</title>
    <link rel="stylesheet" href="./src/style/style.css" >
</head>
<body>
    <!-- // LOGIN -->
    <div class="login">
        <form action="./remote/login.php" method="POST">
            <h1> 
                Log In 
            </h1>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <input type="submit" name="login" value="Log in">
            <p>No account yet? <span id="go_signup">Sign Up</span></p>
        </form>
    </div>
    <!-- // REGISTER -->
    <div class="signup">
        <form action="./remote/signup.php" method="POST">    
        <span id="go_back">Go back</span>
            <h1> 
                Sign Up 
            </h1>
            <label for="fullname">Name:</label>
            <input type="text" id="fullname" name="name">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <input type="submit" name="signup" value="Sign Up">
        </form>
    </div>
    <script src="./src/js/index.js"></script>
</body>
</html>