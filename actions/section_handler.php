<?php
session_start();
require_once '../db/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update':
            $id = $_POST['id'] ?? '';
            $section = $_POST['section'] ?? '';
            
            if ($section === 'education') {
                $institution = $_POST['institutionName'] ?? '';
                $degree = $_POST['degree'] ?? '';
                $start_date = $_POST['startDate'] ?? '';
                $end_date = $_POST['endDate'] ?? '';
                $description = $_POST['description'] ?? '';

                $stmt = $conn->prepare("UPDATE education_entries SET institution = ?, degree = ?, start_date = ?, end_date = ?, description = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("sssssii", $institution, $degree, $start_date, $end_date, $description, $id, $user_id);
            } 
            else if ($section === 'experience') {
                $company = $_POST['companyName'] ?? '';
                $position = $_POST['position'] ?? '';
                $start_date = $_POST['startDate'] ?? '';
                $end_date = $_POST['endDate'] ?? '';
                $description = $_POST['description'] ?? '';

                $stmt = $conn->prepare("UPDATE experience_entries SET company = ?, position = ?, start_date = ?, end_date = ?, description = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("sssssii", $company, $position, $start_date, $end_date, $description, $id, $user_id);
            }
            else if ($section === 'activities') {
                $activity = $_POST['activityName'] ?? '';
                $role = $_POST['role'] ?? '';
                $start_date = $_POST['startDate'] ?? '';
                $end_date = $_POST['endDate'] ?? '';
                $description = $_POST['description'] ?? '';

                $stmt = $conn->prepare("UPDATE activities SET activity = ?, role = ?, start_date = ?, end_date = ?, description = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param("sssssii", $activity, $role, $start_date, $end_date, $description, $id, $user_id);
            }
            break;

        case 'delete':
            $id = $_POST['id'] ?? '';
            $section = $_POST['section'] ?? '';
            
            $table = '';
            switch ($section) {
                case 'education':
                    $table = 'education_entries';
                    break;
                case 'experience':
                    $table = 'experience_entries';
                    break;
                case 'activities':
                    $table = 'activities';
                    break;
            }
            
            if ($table) {
                $stmt = $conn->prepare("DELETE FROM $table WHERE id = ? AND user_id = ?");
                $stmt->bind_param("ii", $id, $user_id);
            }
            break;

        case 'education':
            $institution = $_POST['institution'] ?? '';
            $degree = $_POST['degree'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $description = $_POST['description'] ?? '';

            $stmt = $conn->prepare("INSERT INTO education_entries (user_id, institution, degree, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $user_id, $institution, $degree, $start_date, $end_date, $description);
            break;

        case 'experience':
            $company = $_POST['company'] ?? '';
            $position = $_POST['position'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $description = $_POST['description'] ?? '';

            $stmt = $conn->prepare("INSERT INTO experience_entries (user_id, company, position, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $user_id, $company, $position, $start_date, $end_date, $description);
            break;

        case 'project':
            $title = $_POST['title'] ?? '';
            $role = $_POST['role'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $description = $_POST['description'] ?? '';

            $stmt = $conn->prepare("INSERT INTO projects (user_id, title, role, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $user_id, $title, $role, $start_date, $end_date, $description);
            break;

        case 'skill':
            $skill_name = $_POST['skill_name'] ?? '';

            $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
            $stmt->bind_param("is", $user_id, $skill_name);
            break;

        case 'activity':
            $activity_name = $_POST['activity_name'] ?? '';
            $role = $_POST['role'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $description = $_POST['description'] ?? '';

            $stmt = $conn->prepare("INSERT INTO activities (user_id, activity_name, role, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $user_id, $activity_name, $role, $start_date, $end_date, $description);
            break;

        case 'achievement':
            $title = $_POST['title'] ?? '';
            $date_received = $_POST['date'] ?? '';
            $description = $_POST['description'] ?? '';

            $stmt = $conn->prepare("INSERT INTO achievements (user_id, title, date_received, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $title, $date_received, $description);
            break;

        default:
            $response['message'] = 'Invalid action';
            echo json_encode($response);
            exit;
    }

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Entry saved successfully';
        $response['id'] = $conn->insert_id;
    } else {
        $response['message'] = 'Error saving entry: ' . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>
