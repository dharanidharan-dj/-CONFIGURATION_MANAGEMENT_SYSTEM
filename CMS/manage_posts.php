<?php
include "admin_auth.php";
include "db.php";

if (isset($_POST['add_post'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmp  = $_FILES['image']['tmp_name'];

        $imageName = preg_replace("/[^a-zA-Z0-9.]/", "_", $imageName);
        $newFileName = time() . "_" . $imageName;
        $uploadPath = "uploads/" . $newFileName;

        if (!is_dir("uploads")) {
            mkdir("uploads", 0777, true);
        }

        move_uploaded_file($imageTmp, $uploadPath);
    } else {
        $uploadPath = "";
    }

    $stmt = $conn->prepare("INSERT INTO posts (title, content, image, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $title, $content, $uploadPath);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_posts.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $stmt = $conn->prepare("SELECT image FROM posts WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!empty($row['image']) && file_exists($row['image'])) {
        unlink($row['image']);
    }

    $stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_posts.php");
    exit();
}

$posts = mysqli_query($conn, "SELECT * FROM posts ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Posts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="admin-container">

<?php include "partials/sidebar.php"; ?>

<div class="main-content">
    <div class="topbar">
        <div class="page-title">
            <h1>Manage Posts</h1>
            <p>Create and maintain blog content with media.</p>
        </div>
        <div class="welcome-pill">Editor: <?php echo htmlspecialchars($_SESSION['username']); ?></div>
    </div>

    <div class="card">
        <div class="section-title">
            <h2>Add New Post</h2>
            <p>Publish new content directly to your homepage feed.</p>
        </div>

        <form method="POST" enctype="multipart/form-data" class="form-grid">
            <div>
                <label for="title">Title</label>
                <input id="title" type="text" name="title" required>
            </div>

            <div>
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="6" required></textarea>
            </div>

            <div>
                <label for="image">Image</label>
                <input id="image" type="file" name="image" accept="image/*">
            </div>

            <div>
                <button type="submit" name="add_post">Publish Post</button>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:18px;">
        <div class="section-title">
            <h2>All Posts</h2>
            <p>Review and manage existing published posts.</p>
        </div>

        <div style="overflow:auto;">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($posts)) : ?>
                <tr>
                    <td><?php echo (int) $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td>
                        <?php if (!empty($row['image'])) : ?>
                            <a href="<?php echo htmlspecialchars($row['image']); ?>" target="_blank">
                                <img class="thumb" src="<?php echo htmlspecialchars($row['image']); ?>" alt="Post image">
                            </a>
                        <?php else : ?>
                            <span style="color:#a7bbd8;">No image</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <div class="inline-actions">
                            <a class="btn btn-danger" href="manage_posts.php?delete=<?php echo (int) $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </div>
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
