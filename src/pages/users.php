<?php

include("./nav.php");

try{
  $stmt = $pdo->query("SELECT * FROM users_tbl");
}catch(PDOException $e){
  error_log('Database error:' . $e->getMessage());
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
</style>

<section>
<div class="users-container">
    <div class="users-header">
      <h1>User List</h1>
    </div>
    <table class="users-table">
      <thead>
        <tr>
          <th>User ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if($stmt->rowCount() > 0) { ?>
        <?php while($user = $stmt->fetch()) { ?>
        <tr>
          <td><?php echo $user->userID ?></td>
          <td><?php echo $user->name ?></td>
          <td><?php echo $user->email ?></td>
          <td class="user-action">
            <?php if($user->status !== 'Blocked') { ?>
              <a href="../../remote/manageuser.php?block=yes&name=<?php echo $user->name ?>"><button id="block">Block user</button></a>  
            <?php } ?>
            <?php if($user->status == 'Blocked') {?>
              <a href="../../remote/manageuser.php?block=no&name=<?php echo $user->name ?>"><button id="unblock">Unblock user</button></a>
            <?php } ?>
          </td>
        </tr>
        <?php } ?>
        <?php } else { ?>
                <tr>
                    <td colspan="3">No users</td>
                </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</section>