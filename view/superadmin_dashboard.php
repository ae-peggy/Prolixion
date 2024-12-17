<?php
session_start();
require_once '../db/database.php';
require_once '../utils/SystemLogger.php';

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
    
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete user.";
    }
    $stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle user update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = filter_var($_POST['user_id'], FILTER_SANITIZE_NUMBER_INT);
    $fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
    $lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $role = filter_var($_POST['role'], FILTER_SANITIZE_NUMBER_INT);
    
    $stmt = $conn->prepare("UPDATE users SET fname = ?, lname = ?, email = ?, role = ? WHERE user_id = ?");
    $stmt->bind_param("sssii", $fname, $lname, $email, $role, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update user.";
    }
    $stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Verify if user is superadmin
$stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ? AND role = 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    header('Location: ../view/dashboard.php');
    exit;
}
$stmt->close();

// Fetch system analytics
$analytics = [
    'total_users' => 0,
    'total_admins' => 0,
    'total_projects' => 0,
    'total_feedback' => 0,
    'total_skills' => 0
];

// Get total users count
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$analytics['total_users'] = $result->fetch_assoc()['count'];

// Get admin count
$result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 2");
$analytics['total_admins'] = $result->fetch_assoc()['count'];

// Get total projects
$result = $conn->query("SELECT COUNT(*) as count FROM projects");
$analytics['total_projects'] = $result->fetch_assoc()['count'];

// Get total feedback
$result = $conn->query("SELECT COUNT(*) as count FROM feedback");
$analytics['total_feedback'] = $result->fetch_assoc()['count'];

// Get total skills
$result = $conn->query("SELECT COUNT(*) as count FROM skills");
$analytics['total_skills'] = $result->fetch_assoc()['count'];

// Fetch recent system activities
$recent_activities = [];
$activities_query = "SELECT sa.*, u.fname, u.lname 
                    FROM system_activities sa 
                    LEFT JOIN users u ON sa.user_id = u.user_id 
                    ORDER BY sa.created_at DESC 
                    LIMIT 10";
$result = $conn->query($activities_query);
while ($activity = $result->fetch_assoc()) {
    $recent_activities[] = $activity;
}

// Fetch recent user registrations
$stmt = $conn->prepare("
    SELECT user_id, fname, lname, email, role, created_at 
    FROM users 
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->execute();
$recent_users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/portfolio_b/assets/css/dashboard.css?v=<?php echo time(); ?>" rel="stylesheet">
    <style>
        .superadmin-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: rgba(0, 0, 0, 0.554);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5em;
            color: #4a90e2;
            margin: 10px 0;
        }

        .stat-label {
            color: #e0e0e0;
            font-size: 1.1em;
        }

        .recent-section {
            background-color: rgba(0, 0, 0, 0.554);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .recent-section h2 {
            color: #4a90e2;
            margin-bottom: 20px;
        }

        .user-list, .activity-list {
            list-style: none;
            padding: 0;
        }

        .user-list li, .activity-list li {
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
        }

        .user-list li:last-child, .activity-list li:last-child {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .action-button {
            background-color: rgba(74, 144, 226, 0.2);
            border: 1px solid #4a90e2;
            color: #4a90e2;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-button:hover {
            background-color: #4a90e2;
            color: white;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .sidebar {
            position: fixed;
            left: -250px;
            top: 0;
            height: 100%;
            width: 250px;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            transition: left 0.3s ease;
            z-index: 1000;
            padding-top: 60px;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            padding: 15px 25px;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar ul li a:hover {
            color: #4a90e2;
        }

        .hamburger {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            cursor: pointer;
            background:None;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .hamburger i {
            color: white;
            font-size: 24px;
        }


        .content {
            margin-left: 0;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }
        
        .content h1{
            margin-left: 40px;
            margin-top: 8px;
        }

        .content.active {
            margin-left: 250px;
        }

        /* System Activities Styling */
        .content-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        /* System Activities Styling */
        .content-section {
            background: rgba(18, 17, 17, 0.351);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .content-section h2 {
            color: #3b60b8;
            margin: 0 0 20px 0;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .activities-list {
            margin-top: 20px;
            max-height: 600px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .activities-list::-webkit-scrollbar {
            width: 8px;
        }

        .activities-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .activities-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        .activities-list::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .activity-item {
            background: rgba(20, 20, 20, 0.4);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .activity-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .activity-type {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            margin-right: 15px;
            min-width: 110px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #403b3b;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .activity-type.login { background: linear-gradient(135deg, #4a90e2, #357abd); }
        .activity-type.logout { background: linear-gradient(135deg, #607D8B, #546E7A); }
        .activity-type.user_creation { background: linear-gradient(135deg, #2196F3, #1976D2); }
        .activity-type.user_update { background: linear-gradient(135deg, #FF9800, #F57C00); }
        .activity-type.user_deletion { background: linear-gradient(135deg, #F44336, #D32F2F); }
        .activity-type.profile_update { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
        .activity-type.settings_change { background: linear-gradient(135deg, #795548, #5D4037); }
        
        .activity-details {
            flex: 1;
        }

        .activity-description {
            margin: 0 0 8px 0;
            font-size: 15px;
            color: #fff;
            line-height: 1.4;
            font-weight: 500;
        }

        .activity-meta {
            margin: 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .activity-meta span {
            display: inline-flex;
            align-items: center;
        }

        .activity-meta span::before {
            content: '•';
            margin-right: 15px;
            color: rgba(255, 255, 255, 0.4);
        }

        .activity-meta span:first-child::before {
            display: none;
        }

        .activity-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-edit, .btn-delete {
            padding: 5px 15px;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-weight: 500;
        }

        .btn-edit {
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }

        .btn-edit:hover, .btn-delete:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(30, 30, 30, 0.95);
            padding: 25px;
            border-radius: 15px;
            min-width: 300px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .modal h3 {
            color: #fff;
            margin-bottom: 20px;
        }

        .modal textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            color: #fff;
            min-height: 100px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-save, .btn-cancel {
            padding: 8px 20px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: 500;
        }

        .btn-save {
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: white;
        }

        .btn-cancel {
            background: linear-gradient(135deg, #607D8B, #546E7A);
            color: white;
        }

        /* User Management Styles */
        .user-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .user-card {
            background: rgba(20, 20, 20, 0.4);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.2s ease;
        }

        .user-card:hover {
            transform: translateY(-2px);
        }

        .user-info {
            margin-bottom: 10px;
        }

        .user-name {
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
        }

        .user-email {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .user-role {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            background: rgba(74, 144, 226, 0.2);
            color: #4a90e2;
            margin-top: 5px;
        }

        .edit-form {
            display: none;
            background: rgba(30, 30, 30, 0.95);
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .edit-form input, .edit-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            color: #fff;
        }

        .edit-form select option {
            background: #1e1e1e;
            color: #fff;
        }

        /* Analytics Card Colors */
        .analytics-card.users {
            background: linear-gradient(135deg, #4a90e2, #357abd);
        }
        .analytics-card.admins {
            background: linear-gradient(135deg, #2196F3, #1976D2);
        }
        .analytics-card.projects {
            background: linear-gradient(135deg, #00BCD4, #0097A7);
        }
        .analytics-card.feedback {
            background: linear-gradient(135deg, #03A9F4, #0288D1);
        }
        .analytics-card.skills {
            background: linear-gradient(135deg, #29B6F6, #039BE5);
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

    <nav class="sidebar">
        <ul>
            <li><a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="content">
        <h1>Superadmin Dashboard</h1>
        
        <div class="superadmin-stats">
            <div class="stat-card">
                <i class="fas fa-users fa-2x"></i>
                <div class="stat-number"><?php echo $analytics['total_users']; ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-shield fa-2x"></i>
                <div class="stat-number"><?php echo $analytics['total_admins']; ?></div>
                <div class="stat-label">Admins</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-project-diagram fa-2x"></i>
                <div class="stat-number"><?php echo $analytics['total_projects']; ?></div>
                <div class="stat-label">Projects</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-comments fa-2x"></i>
                <div class="stat-number"><?php echo $analytics['total_feedback']; ?></div>
                <div class="stat-label">Feedback</div>
            </div>
        </div>

        <div class="grid-container">
            <div class="content-section">
                <h2>Recent User Registrations</h2>
                <div class="users-list">
                    <?php foreach ($recent_users as $user): ?>
                        <div class="user-card">
                            <div class="user-info">
                                <div class="user-name"><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?></div>
                                <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                <div class="user-role">
                                    <?php echo $user['role'] == 1 ? 'Superadmin' : 'Admin'; ?>
                                </div>
                            </div>
                            <div class="user-actions">
                                <button class="btn-edit" onclick="toggleEditForm(<?php echo $user['user_id']; ?>)">Edit</button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="hidden" name="delete_user" value="1">
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </div>
                            <!-- Edit Form -->
                            <div id="editForm<?php echo $user['user_id']; ?>" class="edit-form">
                                <form method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="hidden" name="update_user" value="1">
                                    <input type="text" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" placeholder="First Name" required>
                                    <input type="text" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" placeholder="Last Name" required>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Email" required>
                                    <select name="role" required>
                                        <option value="2" <?php echo $user['role'] == 2 ? 'selected' : ''; ?>>Admin</option>
                                        <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Superadmin</option>
                                    </select>
                                    <div class="modal-actions">
                                        <button type="button" class="btn-cancel" onclick="toggleEditForm(<?php echo $user['user_id']; ?>)">Cancel</button>
                                        <button type="submit" class="btn-save">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="content-section">
                <h2>System Activities</h2>
                <div class="activities-list">
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-type <?php echo htmlspecialchars($activity['activity_type']); ?>">
                                <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $activity['activity_type']))); ?>
                            </div>
                            <div class="activity-details">
                                <p class="activity-description">
                                    <?php 
                                    $user_name = $activity['fname'] ? htmlspecialchars($activity['fname'] . ' ' . $activity['lname']) : 'System';
                                    echo htmlspecialchars($activity['description']); 
                                    ?>
                                </p>
                                <div class="activity-meta">
                                    <span>By: <?php echo $user_name; ?></span>
                                    <span>IP: <?php echo htmlspecialchars($activity['ip_address']); ?></span>
                                    <span><?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?></span>
                                </div>
                                <div class="activity-actions">
                                    <button class="btn-edit" onclick="editActivity(<?php echo $activity['activity_id']; ?>, '<?php echo addslashes($activity['description']); ?>')">Edit</button>
                                    <button class="btn-delete" onclick="deleteActivity(<?php echo $activity['activity_id']; ?>)">Delete</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Activity</h3>
            <form id="editForm" method="POST" action="../actions/update_activity.php">
                <input type="hidden" id="editActivityId" name="activity_id">
                <textarea id="editDescription" name="description" placeholder="Activity description"></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMenu() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.content').classList.toggle('active');
        }

        // Edit Activity
        function editActivity(activityId, description) {
            const modal = document.getElementById('editModal');
            const activityIdInput = document.getElementById('editActivityId');
            const descriptionInput = document.getElementById('editDescription');

            activityIdInput.value = activityId;
            descriptionInput.value = description;
            modal.style.display = 'block';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Delete Activity
        function deleteActivity(activityId) {
            if (confirm('Are you sure you want to delete this activity?')) {
                // Create a form dynamically
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '../actions/delete_activity.php';

                // Create hidden input for activity_id
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'activity_id';
                input.value = activityId;

                // Append input to form and form to document
                form.appendChild(input);
                document.body.appendChild(form);

                // Submit the form
                form.submit();
            }
        }

        // Toggle edit form visibility
        function toggleEditForm(userId) {
            const form = document.getElementById('editForm' + userId);
            if (form.style.display === 'none' || !form.style.display) {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }

        // Show success/error messages and fade them out
        <?php if (isset($_SESSION['success_message']) || isset($_SESSION['error_message'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['success_message'])): ?>
                alert('<?php echo addslashes($_SESSION['success_message']); ?>');
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                alert('<?php echo addslashes($_SESSION['error_message']); ?>');
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        });
        <?php endif; ?>
    </script>
</body>
</html>
