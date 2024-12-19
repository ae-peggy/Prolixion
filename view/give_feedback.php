<?php
session_start();
require_once '../db/database.php';

// Create a custom error handler
function myErrorHandler($errno, $errstr, $errfile, $errline) {
    $error_message = date("[Y-m-d H:i:s]") . " Error: [$errno] $errstr in $errfile on line $errline\n";
    error_log($error_message, 3, "../error.log");
}
set_error_handler("myErrorHandler");

// Also log any database errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// Get user ID from URL
$portfolio_user_id = isset($_GET['user']) ? (int)$_GET['user'] : 0;

if (!$portfolio_user_id) {
    die("Invalid request. No user specified.");
}

// Fetch user details
$stmt = $conn->prepare("SELECT fname, lname FROM user WHERE user_id = ?");
$stmt->bind_param("i", $portfolio_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_text = trim($_POST['feedback_text']);
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
    $reviewer_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if (empty($feedback_text)) {
        $error = "Please provide feedback text.";
    } else {
        try {
            $stmt = $conn->prepare("
            INSERT INTO feedback (user_id, reviewer_id, feedback_text, rating, created_at) 
            VALUES (?, NULL, ?, ?, NOW())
            ");
            
            $stmt->bind_param("isi", $portfolio_user_id, $feedback_text, $rating);
            $stmt->execute();
            $success = "Thank you! Your feedback has been submitted successfully.";
            
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Feedback - Portfolio Builder</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="../assets/css/give_feedback.css?v=<?php echo time(); ?>" rel="stylesheet">
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
        <div class="form-container">
            <div class="form-header">
                <h2>Give Feedback</h2>
                <p>Providing feedback for <?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?>'s portfolio</p>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <div class="star-rating">
                        <?php for($i = 5; $i >= 1; $i--): ?>
                            <!-- Ensure the radio button is selected after submission -->
                            <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" 
                                <?php if (isset($_POST['rating']) && $_POST['rating'] == $i) echo 'checked'; ?>
                            >
                            <label for="star<?php echo $i; ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="feedback_text">Your Feedback:</label>
                    <textarea id="feedback_text" name="feedback_text" required><?php echo isset($_POST['feedback_text']) ? htmlspecialchars($_POST['feedback_text']) : ''; ?></textarea>
                </div>

                <button type="submit" class="submit-btn">Submit Feedback</button>
            </form>


            <div class="preview-link">
                <?php 
                // Modify the code to ensure connection remains open
                if (!$conn || $conn->connect_errno) {
                    require_once '..db/database.php';  
                }

                // Fetch the latest resume ID for the user
                $stmt = $conn->prepare("
                    SELECT resume_id 
                    FROM generated_resumes 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC 
                    LIMIT 1
                ");
                $stmt->bind_param("i", $portfolio_user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $latest_resume = $result->fetch_assoc();
                $stmt->close();

                if ($latest_resume) {
                    echo '<a href="../view/preview.php?resume_id=' . htmlspecialchars($latest_resume['resume_id']) . '" target="_blank">View Portfolio</a>';
                } else {
                    echo '<p>No portfolio available to view.</p>';
                }

                // Only close connection at the very end of the script
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById('sideMenu').classList.toggle('active');
        }
    </script>
</body>
</html>
