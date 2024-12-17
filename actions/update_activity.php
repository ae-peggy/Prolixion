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
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Update the activity
    $stmt = $conn->prepare("UPDATE system_activities SET description = ? WHERE activity_id = ?");
    $stmt->bind_param("si", $description, $activity_id);
    
    if ($stmt->execute()) {
        // Log the update
        $systemLogger = new SystemLogger($conn);
        $systemLogger->logActivity($_SESSION['user_id'], 'settings_change', "Updated activity log (ID: $activity_id)");
        
        $_SESSION['success_message'] = "Activity updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update activity.";
    }

    $stmt->close();
    $conn->close();
}

header('Location: ../view/superadmin_dashboard.php');
exit;
?>
