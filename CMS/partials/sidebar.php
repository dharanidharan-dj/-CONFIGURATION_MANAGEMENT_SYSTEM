<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <div class="brand">
        <h2>Neon CMS</h2>
        <p>Professional control center</p>
    </div>

    <a class="nav-link <?php echo $current === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
    <a class="nav-link <?php echo $current === 'manage_posts.php' ? 'active' : ''; ?>" href="manage_posts.php">Manage Posts</a>
    <a class="nav-link <?php echo $current === 'manage_users.php' ? 'active' : ''; ?>" href="manage_users.php">Manage Users</a>
    <a class="nav-link" href="./" target="_blank">View Website</a>
    <a class="nav-link" href="logout.php">Logout</a>
</div>
