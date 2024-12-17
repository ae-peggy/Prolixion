<?php
session_start();
require_once '../db/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Create feedback table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reviewer_id INT,
    feedback_text TEXT NOT NULL,
    rating INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (reviewer_id) REFERENCES users(user_id)
)";

$conn->query($sql);

// Now fetch feedback
$stmt = $conn->prepare("
    SELECT f.*, u.fname, u.lname 
    FROM feedback f 
    LEFT JOIN users u ON f.reviewer_id = u.user_id 
    WHERE f.user_id = ? 
    ORDER BY f.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$feedbacks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Generate unique share link
$shareLink = "//" . $_SERVER['HTTP_HOST'] . "/portfolio_b/view/give_feedback.php?user=" . $user_id;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Feedback</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/portfolio_b/assets/css/feedback.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>
<body>
    <video autoplay muted loop id="myVideo">
        <source src="../assets/images/BG2.mp4" type="video/mp4">
    </video>

    <div class="hamburger" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </div>

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

    <div class="container">
        <div class="feedback-section">
            <div class="section-header">
                <h2>Your Feedback</h2>
                <p>Share and manage feedback for your portfolio</p>
            </div>
            
            <div class="share-options">
                <input type="text" id="share-link" name="share-link" value="<?php echo htmlspecialchars($shareLink); ?>" readonly>
                <button onclick="copyShareLink()">
                    <i class="fas fa-copy"></i> Copy Link
                </button>
            </div>

            <div class="feedback-list">
                <?php if (!empty($feedbacks)): ?>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <div class="feedback-item">
                            <div class="feedback-header">
                                <div class="feedback-author">
                                    <?php echo htmlspecialchars($feedback['fname'] . ' ' . $feedback['lname']); ?>
                                </div>
                                <div class="feedback-date">
                                    <?php echo date('F j, Y', strtotime($feedback['created_at'])); ?>
                                </div>
                            </div>
                            <?php if ($feedback['rating']): ?>
                                <div class="feedback-rating">
                                    <?php 
                                    $rating = $feedback['rating'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            <div class="feedback-text">
                                <?php echo nl2br(htmlspecialchars($feedback['feedback_text'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-feedback">
                        No feedback received yet. Share your portfolio link to get started!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sideMenu').classList.toggle('active');
        }

        function copyShareLink() {
            const copyText = document.getElementById('share-link');
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');
            
            // Optional: Show a tooltip or alert
            alert('Share link copied to clipboard!');
        }
    </script>
</body>
</html>
