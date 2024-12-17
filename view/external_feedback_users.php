<?php
require_once '../db/database.php';
require_once '../actions/generate_feedback_token.php';

// Initialize token manager
$tokenManager = new FeedbackTokenManager($conn);

// Cleanup any expired tokens
$tokenManager->cleanupExpiredTokens();

// Fetch users with portfolios
$users_query = "
    SELECT DISTINCT u.user_id, u.fname, u.lname, u.profile_picture, 
           (SELECT desired_job_title FROM resume_details rd WHERE rd.user_id = u.user_id ORDER BY created_at DESC LIMIT 1) as desired_job_title
    FROM user u
    JOIN generated_resumes gr ON u.user_id = gr.user_id
    WHERE u.profile_picture IS NOT NULL
";
$result = $conn->query($users_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Feedback to Portfolio Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .users-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 1200px;
        }
        .user-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: center;
            width: 250px;
            transition: transform 0.3s ease;
        }
        .user-card:hover {
            transform: scale(1.05);
        }
        .user-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .user-card h3 {
            margin: 10px 0 5px;
        }
        .user-card p {
            color: #666;
            margin-bottom: 15px;
        }
        .feedback-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .feedback-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Choose a Portfolio to Give Feedback</h1>
    <div class="users-container">
        <?php while ($user = $result->fetch_assoc()): ?>
            <div class="user-card">
                <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="<?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?>">
                <h3><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?></h3>
                <p><?php echo htmlspecialchars($user['desired_job_title'] ?? 'No job title'); ?></p>
                <form method="GET" action="give_feedback.php">
                    <?php 
                    // Generate a unique token for this user
                    $token = $tokenManager->generateToken($user['user_id']);
                    ?>
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <input type="hidden" name="user" value="<?php echo $user['user_id']; ?>">
                    <button type="submit" class="feedback-btn">Give Feedback</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
