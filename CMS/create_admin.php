<?php include "header.php"; ?>
include 'db.php';
$pass = password_hash("admin123", PASSWORD_DEFAULT);
$conn->query("INSERT INTO users (username,password,role) VALUES ('admin','$pass','admin')");
echo "Admin created";
?>
