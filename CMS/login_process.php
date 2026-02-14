<?php
session_start();
include "db.php";

$username = $_POST['username'];
$password = $_POST['password'];

// Use prepared statement (secure)
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {

    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {

        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        // 🔥 Redirect based on role
        if ($row['role'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }

        exit();

    } else {
        echo "❌ Wrong password";
    }

} else {
    echo "❌ User not found";
}

$stmt->close();
?>
