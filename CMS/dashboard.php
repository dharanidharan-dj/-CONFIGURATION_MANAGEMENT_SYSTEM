<?php
include "admin_auth.php";
include "db.php";

$postResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM posts");
$postCount = 0;
if ($postResult) {
    $postData = mysqli_fetch_assoc($postResult);
    $postCount = (int) $postData['total'];
}

$userCount = 0;
$userResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
if ($userResult) {
    $userData = mysqli_fetch_assoc($userResult);
    $userCount = (int) $userData['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="admin-container">

<?php include "partials/sidebar.php"; ?>

<div class="main-content">
    <div class="topbar">
        <div class="page-title">
            <h1>Dashboard</h1>
            <p>Track content performance and quickly manage your CMS.</p>
        </div>

        <div class="welcome-pill">
            Signed in as <?php echo htmlspecialchars($_SESSION['username']); ?>
        </div>
    </div>

    <div class="grid-stats">
        <div class="card">
            <div class="stat-value"><?php echo $postCount; ?></div>
            <div class="stat-label">Total Posts</div>
        </div>

        <div class="card">
            <div class="stat-value"><?php echo $userCount; ?></div>
            <div class="stat-label">Total Users</div>
        </div>

        <div class="card">
            <div class="stat-value"><?php echo date("d M Y"); ?></div>
            <div class="stat-label">Today</div>
        </div>
    </div>

    <div class="card" style="margin-top:18px;">
        <div class="section-title">
            <h2>Quick Actions</h2>
            <p>Use shortcuts to manage your platform faster.</p>
        </div>

        <div class="actions">
            <a class="btn" href="manage_posts.php">Go to Posts</a>
            <a class="btn btn-secondary" href="manage_users.php">Manage Users</a>
            <a class="btn btn-danger" href="logout.php">Logout</a>
        </div>
    </div>
</div>

</div>

</body>
</html>
