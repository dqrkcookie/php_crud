<?php

include("./nav.php");

if($_SESSION['admin'] == 'content manager' || $_SESSION['admin'] == 'sales representative'){
  echo "<script>alert('Only Administrator has the permission to enter this page!');
    window.location.href = './admin.php?access=denied';
    </script>";
}

try{
  $stmt = $pdo->query("SELECT * FROM shapi_admin")->fetchAll();
}catch(PDOException $e){
  error_log('Database error:' . $e->getMessage());
}

if(isset($_POST['add'])){
  $username = $_POST['username'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  $query = $pdo->query("INSERT INTO shapi_admin(username,password,role)VALUES('$username','$password','$role')");
  header("Location: ./adminlist.php?admin=added");
}

if(isset($_GET['mod_role'])){
  $role = $_GET['m_role'];
  $username = $_GET['username'];

  $query = $pdo->query("UPDATE shapi_admin SET role = '$role' WHERE username = '$username'");
  header("Location: ./adminlist.php?role=modified");
}

?>

<style>
    #block, #unblock {
    padding: 10px 20px;
    border: 1px solid #ccc;
    background-color: #f0f0f0;
    color: #333;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    width: 140px;
  }

  .user-action{ 
    display: flex;
    justify-content: center;
  }

  #block:hover, #unblock:hover {
    background-color: #e0e0e0;  
  }
  
  a{
    text-decoration: none;
  }

  #add{
    margin: 1rem;
  }

#shapi_admin {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  padding: 20px;
  width: 320px;
  font-family: 'Arial', sans-serif;
  z-index: 1000;
}

#shapi_admin form {
  display: flex;
  flex-direction: column;
}

#shapi_admin h1{
  text-align: center;
  color: #333;
  margin-bottom: 6px;
}

#shapi_admin label {
  font-size: 14px;
  color: #333;
  font-weight: 500;
}

#shapi_admin input,
#shapi_admin select {
  width: 100%;
  padding: 8px 10px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-bottom: 8px;
}

#shapi_admin input:focus,
#shapi_admin select:focus {
  border-color: #5b6edb;
  outline: none;
  box-shadow: 0 0 3px rgba(0, 123, 255, 0.5);
}

#shapi_admin button {
  background-color: #5b6edb;
  color: white;
  font-size: 14px;
  font-weight: 600;
  border: none;
  border-radius: 4px;
  padding: 10px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

#shapi_admin button:hover {
  background-color: #4d5eb9;
}

button[popovertarget="shapi_admin"],
#m-btn {
  background-color: #5b6edb; 
  color: #fff; 
  font-size: 14px;
  font-weight: 600;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
}

button[popovertarget="shapi_admin"]:hover,
#m-btn:hover {
  background-color: #4d5eb9;
}

.mod {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #ffffff;
  border: 1px solid #dddddd;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  padding: 20px;
  width: 350px;
  font-family: Arial, sans-serif;
  text-align: center;
}

.mod h1 {
  font-size: 18px;
  margin-bottom: 15px;
  color: #333333;
}

.modform {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.mod select {
  width: 100%;
  padding: 10px;
  border: 1px solid #cccccc;
  border-radius: 4px;
  font-size: 14px;
}

.mod button {
  background-color: #5b6edb;
  color: white;
  font-size: 14px;
  padding: 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 6px;
}

.mod button:hover {
  background-color: #4d5eb9;
}

</style>

<section>
  <div>
    <button popovertarget="shapi_admin" id="add">Add account</button>
  </div>
  <div id="shapi_admin" popover>
    <h1>New admin account</h1>
    <form action="./adminlist.php" method="POST">
      <label>Username</label>
      <input type="text" name="username">
      <label>Password</label>
      <input type="password" name="password">
      <select name="role">
        <option value="">Select role</option>
        <option value="administrator">Administrator</option>
        <option value="content manager">Content manager</option>
        <option value="sales representative">Sales representative</option>
      </select>
      <button name="add">Add now</button>
    </form>
  </div>
<div class="users-container">
    <div class="users-header">
      <h1>Admin List</h1>
    </div>
    <table class="users-table">
      <thead>
        <tr>
          <th>Username</th>
          <th>Password</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stmt as $data) { ?>
          <tr>
            <td><?php echo $data->username ?></td>
            <td><?php echo $data->password ?></td>
            <td><?php echo $data->role ?></td>
            <td><button popovertarget="modify<?php echo $data->username ?>" id="m-btn">Modify</button></td>
            <div class="mod" popover id="modify<?php echo $data->username ?>">
              <h1>Mofidy <?php echo $data->username ?>'s Role/Permission</h1>
              <form action="./adminlist.php" method="GET">
                <input type="hidden" value="<?php echo $data->username ?>" name="username">
                <select name="m_role">
                  <option value="">Select role</option>
                  <option value="administrator">Administrator</option>
                  <option value="content manager">Content manager</option>
                  <option value="sales representative">Sales representative</option>
                </select>
                <button name="mod_role">Save changes</button>
              </form>
            </div>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</section>