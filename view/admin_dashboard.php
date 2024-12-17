<?php
session_start();
require_once '../db/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Verify if user is admin
$stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ? AND role = 2");
$stmt->bind_param("i", $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    header('Location: ../view/dashboard.php');
    exit;
}
$stmt->close();

// Fetch analytics data
$stmt = $conn->prepare("
    SELECT 
        (SELECT COUNT(*) FROM feedback WHERE user_id = ?) as feedback_count,
        (SELECT COUNT(*) FROM projects WHERE user_id = ?) as projects_count,
        (SELECT COUNT(*) FROM skills WHERE user_id = ?) as skills_count,
        (SELECT COUNT(*) FROM social_profiles WHERE user_id = ?) as social_count
");
$stmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
$stmt->execute();
$analytics = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch recent feedback
$stmt = $conn->prepare("
    SELECT f.*, u.fname, u.lname 
    FROM feedback f 
    JOIN users u ON f.reviewer_id = u.user_id 
    WHERE f.user_id = ? 
    ORDER BY f.created_at DESC 
    LIMIT 5
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$feedbacks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch the most recent resume for the user
$stmt = $conn->prepare("
    SELECT resume_id, resume_data, created_at 
    FROM generated_resumes 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$latest_resume = $result->fetch_assoc();
$stmt->close();

// Function to preview resume
function previewResume($resume_id) {
    echo "<script>
        function openResumePreview() {
            window.open('preview.php?resume_id=" . htmlspecialchars($resume_id) . "', '_blank');
        }
    </script>";
}

// If a resume exists, add preview functionality
if ($latest_resume) {
    previewResume($latest_resume['resume_id']);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/portfolio_b/assets/css/dashboard.css?v=<?php echo time(); ?>" rel="stylesheet">
    <style>
        .feedback-carousel {
            background-color: rgba(0, 0, 0, 0.554);
            border-radius: 10px;
            padding: 30px;
            margin-top: 30px;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .feedback-slide {
            display: none;
            text-align: center;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .feedback-slide.active {
            display: block;
            opacity: 1;
        }

        .feedback-slide blockquote {
            font-style: italic;
            margin-bottom: 15px;
            color: #e0e0e0;
        }

        .feedback-slide .feedback-author {
            color: #4a90e2;
            font-weight: bold;
        }

        .carousel-controls {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .carousel-control {
            background-color: rgba(74, 144, 226, 0.2);
            border: 1px solid #4a90e2;
            color: #4a90e2;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-control:hover {
            background-color: #4a90e2;
            color: #121212;
        }

        .content {
            margin-left: 0;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        .content.show {
            margin-left: 250px;
        }

        .logout-link {
            color: #ff4444 !important;
        }

        .logout-link:hover {
            color: #ff6666 !important;
        }
    </style>
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

    <div class="content">
        <div class="dashboard-container">
            <section class="analytics-section">
                <div class="analytics-card">
                    <h3>Total Feedback</h3>
                    <div class="analytics-value"><?php echo $analytics['feedback_count']; ?></div>
                    <div class="analytics-trend">
                        <i class="fas fa-arrow-up"></i> New feedback this week
                    </div>
                </div>
                <div class="analytics-card">
                    <h3>Projects</h3>
                    <div class="analytics-value"><?php echo $analytics['projects_count']; ?></div>
                    <div class="analytics-trend">
                        <i class="fas fa-check"></i> Portfolio items
                    </div>
                </div>
                <div class="analytics-card">
                    <h3>Skills</h3>
                    <div class="analytics-value"><?php echo $analytics['skills_count']; ?></div>
                    <div class="analytics-trend">
                        <i class="fas fa-star"></i> Showcased skills
                    </div>
                </div>
                <div class="analytics-card">
                    <h3>Social Profiles</h3>
                    <div class="analytics-value"><?php echo $analytics['social_count']; ?></div>
                    <div class="analytics-trend">
                        <i class="fas fa-link"></i> Connected profiles
                    </div>
                </div>
            </section>

            <section class="feedback-carousel">
                <div id="feedback-container">
                    <?php if (!empty($feedbacks)): ?>
                        <?php foreach ($feedbacks as $index => $feedback): ?>
                            <div class="feedback-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                <blockquote>
                                    "<?php echo htmlspecialchars($feedback['feedback_text']); ?>"
                                </blockquote>
                                <div class="feedback-author">
                                    - <?php echo htmlspecialchars($feedback['fname'] . ' ' . $feedback['lname']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="feedback-slide active">
                            <blockquote>No feedback received yet. Share your portfolio to get started!</blockquote>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (count($feedbacks) > 1): ?>
                    <div class="carousel-controls">
                        <button class="carousel-control" onclick="prevFeedback()">Previous</button>
                        <button class="carousel-control" onclick="nextFeedback()">Next</button>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <div class="export-section">
            <?php if ($latest_resume): ?>
            <a href="../view/preview.php" class="export-button preview-portfolio-btn"><i class="fas fa-eye"></i> Preview Portfolio</a>
            <?php endif; ?>
            <a href="../view/feedback.php" class="export-button">
                <i class="fas fa-share"></i> Share & Get Feedback
            </a>
        </div>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sideMenu').classList.toggle('show');
            document.querySelector('.content').classList.toggle('show');
        }

        // Feedback Carousel
        let currentSlide = 0;
        const slides = document.querySelectorAll('.feedback-slide');

        function nextFeedback() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        function prevFeedback() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        // Auto-rotate slides if there are multiple slides
        if (slides.length > 1) {
            setInterval(nextFeedback, 5000);
        }
    </script>
    <script>
        // Preview Resume Functionality
        <?php if ($latest_resume): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const previewButtons = document.querySelectorAll('.preview-portfolio-btn');
            previewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    openResumePreview();
                });
            });
        });
        <?php endif; ?>
    </script>
</body>
</html>