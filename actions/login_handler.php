<?php
session_start();
require_once '../db/database.php';
require_once '../utils/SystemLogger.php';

// Initialize system logger
$systemLogger = new SystemLogger($conn);

// Validate login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT user_id, fname, lname, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['lname'] = $user['lname'];
            $_SESSION['role'] = $user['role'];

            // Log the successful login
            $systemLogger->logLogin($user['user_id']);

            // Redirect based on role
            if ($user['role'] == 1) {
                header("Location: ../view/superadmin_dashboard.php");
            } else {
                header("Location: ../view/admin_dashboard.php");
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid password.";
            header("Location: ../view/login.php");
            exit();
        }
    } else {
        // Set error message and redirect to login page
        $_SESSION['login_error'] = "Invalid email or user not found.";
        header("Location: ../view/login.php");
        exit();
    }

    // Close statement
    $stmt->close();
}
