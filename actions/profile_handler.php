<?php
session_start();
require_once '../db/database.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Handle profile updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $bio = mysqli_real_escape_string($conn, $_POST['bio']);
        $userId = $_SESSION['user_id'] ?? 1; // Default to 1 for testing, should use actual session ID
        
        // Handle file upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $target_dir = "../uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            // Check if image file is actual image
            $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    $sql = "UPDATE users SET profile_picture = ?, bio = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssi", $target_file, $bio, $userId);
                    $stmt->execute();
                }
            }
        } else {
            $sql = "UPDATE users SET bio = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $bio, $userId);
            $stmt->execute();
        }
    }
    
    // Handle social profile updates
    if (isset($_POST['update_social'])) {
        $userId = $_SESSION['user_id'] ?? 1; // Default to 1 for testing
        
        $platforms = [
            'linkedin' => $_POST['linkedin_url'],
            'github' => $_POST['github_url'],
            'twitter' => $_POST['twitter_url'],
            'instagram' => $_POST['instagram_url']
        ];
        
        foreach ($platforms as $platform => $url) {
            if (!empty($url)) {
                // Check if platform exists for user
                $sql = "INSERT INTO social_profiles (user_id, platform, profile_url) 
                        VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE profile_url = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isss", $userId, $platform, $url, $url);
                $stmt->execute();
            }
        }
    }
    
    // Redirect back to profile page
    header("Location: ../view/profile.php");
    exit();
}
?>
