<?php
include "admin_auth.php";
include "db.php";

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    if ($id != $_SESSION['id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: manage_users.php");
    exit();
}

if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, role, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $role, $password);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_users.php");
    exit();
}

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="admin-container">

<?php include "partials/sidebar.php"; ?>

<div class="main-content">
    <div class="topbar">
        <div class="page-title">
            <h1>Manage Users</h1>
            <p>Create user accounts and assign dashboard permissions.</p>
        </div>
        <div class="welcome-pill">Admin: <?php echo htmlspecialchars($_SESSION['username']); ?></div>
    </div>

    <div class="card">
        <div class="section-title">
            <h2>Add New User</h2>
            <p>Create a secure user account with role access.</p>
        </div>

        <form method="POST" class="form-grid" style="max-width:620px;">
            <div>
                <label for="username">Username</label>
                <input id="username" type="text" name="username" required>
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div>
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <div>
                <button type="submit" name="add_user">Add User</button>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:18px;">
        <div class="section-title">
            <h2>All Users</h2>
            <p>Manage account access for the CMS panel.</p>
        </div>

        <div style="overflow:auto;">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo (int) $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <td>
                        <?php if ((int) $row['id'] === (int) $_SESSION['id']) : ?>
                            <span style="color:#45f2a5;font-weight:600;">Current User</span>
                        <?php else : ?>
                            <a class="btn btn-danger" href="?delete=<?php echo (int) $row['id']; ?>" onclick="return confirm('Delete this user?');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>

</div>

</body>
</html>
