<?php
session_start();
require_once '../db/database.php';
require_once '../utils/SystemLogger.php';

// Check if user is logged in and is superadmin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: ../view/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_id = filter_var($_POST['activity_id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete the activity
    $stmt = $conn->prepare("DELETE FROM system_activities WHERE activity_id = ?");
    $stmt->bind_param("i", $activity_id);
    
    if ($stmt->execute()) {
        // Log the deletion
        $systemLogger = new SystemLogger($conn);
        $systemLogger->logActivity($_SESSION['user_id'], 'settings_change', "Deleted activity log (ID: $activity_id)");
        
        $_SESSION['success_message'] = "Activity deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete activity.";
    }

    $stmt->close();
    $conn->close();
}

header('Location: ../view/superadmin_dashboard.php');
exit;

