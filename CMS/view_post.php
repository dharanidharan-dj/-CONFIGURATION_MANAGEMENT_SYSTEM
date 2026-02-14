<?php
include "db.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    echo "Post not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($post['title']); ?> | Neon CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/public.css">
</head>
<body>

<div class="detail-wrap">
    <a class="back-link" href="index.php">Back to Home</a>

    <article class="detail-card">
        <?php if (!empty($post['image'])) : ?>
            <img class="post-thumb" src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
        <?php endif; ?>

        <div class="detail-content">
            <h1 class="detail-title"><?php echo htmlspecialchars($post['title']); ?></h1>
            <div class="detail-date"><?php echo htmlspecialchars($post['created_at']); ?></div>
            <div class="detail-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
        </div>
    </article>
</div>

</body>
</html>
