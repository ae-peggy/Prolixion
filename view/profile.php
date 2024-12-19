<?php
session_start();
require_once '../db/database.php';

if (isset($_POST['update_profile'])) {
    $userId = $_SESSION['user_id'];
    $uploadDir = '../uploads/'; // Directly use the uploads folder

    // Ensure upload directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $profilePictureUrl = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $uploadFile = $uploadDir . basename($_FILES['profile_picture']['name']);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        
        // Validate file type and move the uploaded file
        if (in_array($fileExtension, $allowedExtensions)) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
                $profilePictureUrl = str_replace('../', '', $uploadFile); // Use relative path
            } else {
                $_SESSION['error'] = "Failed to move the uploaded file.";
                header("Location: ../view/profile.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Allowed types: " . implode(', ', $allowedExtensions);
            header("Location: ../view/profile.php");
            exit;
        }
    }

    // Update database with bio and profile picture (if available)
    $bio = isset($_POST['bio']) ? htmlspecialchars($_POST['bio'], ENT_QUOTES, 'UTF-8') : '';

    if (!empty($profilePictureUrl)) {
        $query = "UPDATE user SET bio = ?, profile_picture = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $bio, $profilePictureUrl, $userId);
    } else {
        $query = "UPDATE user SET bio = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $bio, $userId);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update the profile.";
    }

    // Redirect back to profile page
    header("Location: ../view/profile.php");
    exit;
}

if (isset($_POST['update_social'])) {
    $userId = $_SESSION['user_id'];

    $socialProfiles = [
        'linkedin' => $_POST['linkedin_url'] ?? '',
        'github' => $_POST['github_url'] ?? '',
        'twitter' => $_POST['twitter_url'] ?? '',
        'instagram' => $_POST['instagram_url'] ?? '',
    ];

    // Remove old entries for this user
    $query = "DELETE FROM social_profiles WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Insert updated data
    $query = "INSERT INTO social_profiles (user_id, platform, profile_url) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    foreach ($socialProfiles as $platform => $url) {
        if (!empty($url)) {
            $stmt->bind_param("iss", $userId, $platform, $url);
            $stmt->execute();
        }
    }

    $_SESSION['success'] = "Social profiles updated successfully.";
    header("Location: ../view/profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/profile.css?v=<?php echo time(); ?>">
</head>
<body>
    <video autoplay muted loop id="myVideo">
        <source src="../assets/images/BG3.mp4" type="video/mp4">
    </video>

    <div class="hamburger" onclick="toggleMenu()">☰</div>
    <nav class="side-menu" id="sideMenu">
        <div class="menu-header">
            <h1>Portfolio Builder</h1>
        </div>
        <ul class="menu-items">
            <li><a href="../view/dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="../view/profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="../view/feedback.php"><i class="fas fa-comments"></i> Feedback</a></li>
            <li><a href="#" onclick="event.preventDefault(); alert('Please use the Preview Portfolio button in the dashboard to preview your portfolio after it has been created 😊.');"><i class="fas fa-eye"></i> Portfolio Preview</a></li>
            <li><a href="../view/resume_builder.php"><i class="fas fa-file-alt"></i> Resume Builder</a></li>
            <li><a href="../actions/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <div class="main-content">
        <section class="profile-section">
            <div class="profile-picture-container">
                <img src="<?php echo !empty($userData['profile_picture']) ? '../' . htmlspecialchars($userData['profile_picture']) : '../assets/images/default-profile.png'; ?>" 
                alt="Profile Picture" class="profile-picture" id="profile-preview">
            </div>

            <h2>Personal Information</h2>
            <form action="../actions/profile_handler.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profile-picture">Update Profile Picture</label>
                    <input type="file" id="profile-picture" name="profile_picture" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="5" placeholder="Tell us about yourself..."><?php echo isset($userData['bio']) ? htmlspecialchars($userData['bio']) : ''; ?></textarea>
                </div>
                <button type="submit" name="update_profile">Save Profile</button>
            </form>
        </section>

        <section class="profile-section">
            <h2>Connect Your Social Profiles</h2>
            <form action="../actions/profile_handler.php" method="POST">
                <div class="social-profile">
                    <label for="linkedin-url">LinkedIn</label>
                    <i class="fab fa-linkedin"></i>
                    <input type="url" id="linkedin-url" name="linkedin_url" 
                           value="<?php echo isset($socialProfiles['linkedin']) ? htmlspecialchars($socialProfiles['linkedin']) : ''; ?>" 
                           placeholder="Your LinkedIn URL">
                </div>
                <div class="social-profile">
                    <label for="github-url">GitHub</label>
                    <i class="fab fa-github"></i>
                    <input type="url" id="github-url" name="github_url" 
                           value="<?php echo isset($socialProfiles['github']) ? htmlspecialchars($socialProfiles['github']) : ''; ?>" 
                           placeholder="Your GitHub URL">
                </div>
                <div class="social-profile">
                    <label for="twitter-url">Twitter</label>
                    <i class="fab fa-twitter"></i>
                    <input type="url" id="twitter-url" name="twitter_url" 
                           value="<?php echo isset($socialProfiles['twitter']) ? htmlspecialchars($socialProfiles['twitter']) : ''; ?>" 
                           placeholder="Your Twitter URL">
                </div>
                <div class="social-profile">
                    <label for="instagram-url">Instagram</label>
                    <i class="fab fa-instagram"></i>
                    <input type="url" id="instagram-url" name="instagram_url" 
                           value="<?php echo isset($socialProfiles['instagram']) ? htmlspecialchars($socialProfiles['instagram']) : ''; ?>" 
                           placeholder="Your Instagram URL">
                </div>
                <button type="submit" name="update_social">Save Social Profiles</button>
            </form>
        </section>
    </div>
    
    <script>
        function toggleMenu() {
            var sideMenu = document.getElementById("sideMenu");
            sideMenu.classList.toggle("show");
        }

        document.getElementById('profile-picture').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>
