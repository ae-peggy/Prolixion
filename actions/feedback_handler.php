<?php
session_start();
require_once '../db/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback_text = trim($_POST['feedback_text']);
    $portfolio_user_id = $_POST['portfolio_user_id'];
    $reviewer_id = $_SESSION['user_id'] ?? null;

    if (empty($feedback_text) || empty($portfolio_user_id) || empty($reviewer_id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Insert feedback
    $stmt = $conn->prepare("
        INSERT INTO feedback (user_id, reviewer_id, feedback_text, created_at) 
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->bind_param("iis", $portfolio_user_id, $reviewer_id, $feedback_text);

    if ($stmt->execute()) {
        // Get the feedback details with reviewer name
        $stmt = $conn->prepare("
            SELECT f.*, u.fname, u.lname 
            FROM feedback f 
            JOIN user u ON f.reviewer_id = u.user_id 
            WHERE f.feedback_id = LAST_INSERT_ID()
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        $feedback = $result->fetch_assoc();

        // Format the response
        $response = [
            'success' => true,
            'feedback' => [
                'author' => $feedback['fname'] . ' ' . $feedback['lname'],
                'date' => date('M d, Y', strtotime($feedback['created_at'])),
                'content' => $feedback['feedback_text']
            ]
        ];
        echo json_encode($response);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save feedback']);
    }

    $stmt->close();
    $conn->close();
}
