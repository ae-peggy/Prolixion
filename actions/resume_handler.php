<?php
session_start();
require_once '../db/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Please log in first';
    header('Location: ../view/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to format date to database format
function format_date($date) {
    if (empty($date)) return null;
    $timestamp = strtotime($date);
    return $timestamp ? date('Y-m-d', $timestamp) : null;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['section'] ?? '';
    $action = $_POST['action'] ?? '';

    // Handle different actions based on the section
    switch ($action) {
        case 'generate':
            // Fetch all user's resume data
            $resume_data = [];
            
            // Fetch education entries
            $stmt = $conn->prepare("SELECT * FROM education_entries WHERE user_id = ? ORDER BY start_date DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $resume_data['education'] = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            // Fetch experience entries
            $stmt = $conn->prepare("SELECT * FROM experience_entries WHERE user_id = ? ORDER BY start_date DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $resume_data['experience'] = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            // Fetch skills
            $stmt = $conn->prepare("SELECT * FROM skills WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $resume_data['skills'] = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            // Fetch projects
            $stmt = $conn->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY start_date DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $resume_data['projects'] = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            // Fetch activities
            $stmt = $conn->prepare("SELECT * FROM activities WHERE user_id = ? ORDER BY start_date DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $resume_data['activities'] = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            // Fetch achievements
            $stmt = $conn->prepare("SELECT * FROM achievements WHERE user_id = ? ORDER BY date_received DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $resume_data['achievements'] = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            // Store the generated resume
            $resume_name = sanitize_input($_POST['resume_name']);
            $resume_data_json = json_encode($resume_data);
            
            $stmt = $conn->prepare("INSERT INTO generated_resumes (user_id, resume_name, resume_data) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $resume_name, $resume_data_json);
            
            if ($stmt->execute()) {
                $resume_id = $stmt->insert_id;
                header("Location: preview.php?resume_id=" . $resume_id);
                exit;
            } else {
                $_SESSION['error'] = 'Error generating resume';
                header('Location: ../view/resume_builder.php');
                exit;
            }
            $stmt->close();
            break;

        case 'add':
            switch ($section) {
                case 'education':
                    $institution = sanitize_input($_POST['institution']);
                    $degree = sanitize_input($_POST['degree']);
                    $start_date = format_date($_POST['start_date'] ?? '');
                    $end_date = format_date($_POST['end_date'] ?? '');
                    $description = sanitize_input($_POST['description'] ?? '');

                    $stmt = $conn->prepare("INSERT INTO education_entries (user_id, institution, degree, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssss", $user_id, $institution, $degree, $start_date, $end_date, $description);
                    break;

                case 'experience':
                    $company = sanitize_input($_POST['company']);
                    $position = sanitize_input($_POST['position']);
                    $start_date = format_date($_POST['start_date'] ?? '');
                    $end_date = format_date($_POST['end_date'] ?? '');
                    $description = sanitize_input($_POST['description'] ?? '');

                    $stmt = $conn->prepare("INSERT INTO experience_entries (user_id, company, position, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssss", $user_id, $company, $position, $start_date, $end_date, $description);
                    break;

                case 'projects':
                    $title = sanitize_input($_POST['title']);
                    $role = sanitize_input($_POST['role'] ?? '');
                    $description = sanitize_input($_POST['description'] ?? '');
                    $start_date = format_date($_POST['start_date'] ?? '');
                    $end_date = format_date($_POST['end_date'] ?? '');

                    $stmt = $conn->prepare("INSERT INTO projects (user_id, title, role, description, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssss", $user_id, $title, $role, $description, $start_date, $end_date);
                    break;

                case 'activities':
                    $activity_name = sanitize_input($_POST['activity_name']);
                    $role = sanitize_input($_POST['role'] ?? '');
                    $start_date = format_date($_POST['start_date'] ?? '');
                    $end_date = format_date($_POST['end_date'] ?? '');
                    $description = sanitize_input($_POST['description'] ?? '');

                    $stmt = $conn->prepare("INSERT INTO activities (user_id, activity_name, role, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("isssss", $user_id, $activity_name, $role, $start_date, $end_date, $description);
                    break;

                case 'achievements':
                    $title = sanitize_input($_POST['title']);
                    $date_received = format_date($_POST['date'] ?? '');
                    $description = sanitize_input($_POST['description'] ?? '');

                    $stmt = $conn->prepare("INSERT INTO achievements (user_id, title, date_received, description) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isss", $user_id, $title, $date_received, $description);
                    break;

                case 'skills':
                    $skill_name = sanitize_input($_POST['skill_name']);

                    $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name) VALUES (?, ?)");
                    $stmt->bind_param("is", $user_id, $skill_name);
                    break;
            }

            if (isset($stmt)) {
                if ($stmt->execute()) {
                    $_SESSION['success'] = ucfirst($section) . ' added successfully';
                } else {
                    $_SESSION['error'] = 'Error adding ' . $section;
                }
                $stmt->close();
            }
            break;

        case 'delete':
            $id = $_POST['id'] ?? 0;
            
            switch ($section) {
                case 'education':
                    $table = 'education_entries';
                    $id_column = 'education_id';
                    break;
                case 'experience':
                    $table = 'experience_entries';
                    $id_column = 'experience_id';
                    break;
                case 'skills':
                    $table = 'skills';
                    $id_column = 'skill_id';
                    break;
                case 'projects':
                    $table = 'projects';
                    $id_column = 'project_id';
                    break;
                case 'activities':
                    $table = 'activities';
                    $id_column = 'activity_id';
                    break;
                case 'achievements':
                    $table = 'achievements';
                    $id_column = 'achievement_id';
                    break;
            }

            if (isset($table)) {
                $stmt = $conn->prepare("DELETE FROM $table WHERE $id_column = ? AND user_id = ?");
                $stmt->bind_param("ii", $id, $user_id);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = ucfirst($section) . ' deleted successfully';
                } else {
                    $_SESSION['error'] = 'Error deleting ' . $section;
                }
                $stmt->close();
            }
            break;
    }
    
    // Redirect back to resume builder unless we're generating a preview
    if ($action !== 'generate') {
        header('Location: ../view/resume_builder.php');
        exit;
    }
}

$conn->close();
?>
