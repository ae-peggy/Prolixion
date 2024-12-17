<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

// Redirect based on role
if ($_SESSION['role'] == 2) {
    header("Location: ../view/admin_dashboard.php");
    exit;
} elseif ($_SESSION['role'] == 1) {
    header("Location: ../view/superadmin_dashboard.php");
    exit;
} else {
    echo "Error: Unknown role.";
    exit();
}
