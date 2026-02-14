<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard | Neon CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/public.css">
</head>
<body>

<div class="user-panel">
    <div class="panel-card">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Your account is active. Use the actions below to continue browsing the platform.</p>

        <div class="panel-actions">
            <a class="btn" href="index.php">View Posts</a>
            <a class="btn secondary" href="logout.php">Logout</a>
        </div>
    </div>
</div>

</body>
</html>
