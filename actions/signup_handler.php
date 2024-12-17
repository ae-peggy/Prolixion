<?php
session_start();
require_once '../db/database.php';

// Function to sanitize input
function sanitizeInput($input) {
    $input = trim($input); // Remove spaces
    $input = stripslashes($input); // Remove backslashes
    return htmlspecialchars($input); // Convert special characters to HTML entities
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $fname = sanitizeInput($_POST['fname']);
    $lname = sanitizeInput($_POST['lname']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    $errors = [];

    // Basic validation
    if (empty($fname) || empty($lname)) {
        $errors[] = "Full name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Email already exists.";
    }

    // If no errors, insert user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user (fname, lname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fname, $lname, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: ../view/login.php"); // Redirect to login
            exit();
        } else {
            $errors[] = "Registration failed. Try again.";
        }
    }

    // If there are errors, show them
    $_SESSION['errors'] = $errors;
    header("Location: ../view/signup.php");
    exit();
}
