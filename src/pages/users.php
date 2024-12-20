<?php

include("./nav.php");

try{
  $stmt = $pdo->query("SELECT * FROM users_tbl");
}catch(PDOException $e){
  error_log('Database error:' . $e->getMessage());
}

?>

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
        </tr>
      </thead>
      <tbody>
        <?php while($user = $stmt->fetch()) { ?>
        <tr>
          <td><?php echo $user->userID ?></td>
          <td><?php echo $user->name ?></td>
          <td><?php echo $user->email ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</section>