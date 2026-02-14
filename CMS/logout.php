<?php
session_start();
session_destroy();
if ($row['role'] == 'admin') {
    header("Location: admin_dashboard.php");
} else {
    header("Location: user_dashboard.php");
}
exit();
?>
