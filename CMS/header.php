<?php
include "admin_auth.php";
?>

<div style="background:#eee;padding:10px;">
    Welcome <b><?php echo $_SESSION['username']; ?></b> |
    Role: <?php echo $_SESSION['role']; ?> |
    <a href="logout.php">Logout</a>
</div>
<hr>
