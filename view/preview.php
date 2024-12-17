<?php
session_start();
require_once '../db/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$resume_id = $_GET['resume_id'] ?? null;

// Fetch resume data
if ($resume_id) {
    $stmt = $conn->prepare("SELECT * FROM generated_resumes WHERE resume_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $resume_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resume = $result->fetch_assoc();
    $stmt->close();

    if (!$resume) {
        header('Location: ../view/resume_builder.php');
        exit;
    }

    $resume_data = json_decode($resume['resume_data'], true);
} else {
    header('Location: ../view/resume_builder.php');
    exit;
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch social profiles
$stmt = $conn->prepare("SELECT platform, profile_url FROM social_profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$social_profiles = [];
while ($row = $result->fetch_assoc()) {
    $social_profiles[$row['platform']] = $row['profile_url'];
}
$stmt->close();

// Fetch achievements
$stmt = $conn->prepare("SELECT * FROM achievements WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$achievements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch activities
$stmt = $conn->prepare("SELECT * FROM activities WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$activities = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?> - Resume</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="/portfolio_b/assets/css/preview.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>
<body>
    <div class="hamburger" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </div>
    
    <div id="sideMenu">
        <a href="../view/dashboard.php"><i class="fas fa-home"></i>Dashboard</a>
        <a href="../view/resume_builder.php"><i class="fas fa-edit"></i>Resume Builder</a>
        <a href="../view/feedback.php"><i class="fas fa-comments"></i>Feedback</a>
        <a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>

    <div class="resume-container" id="resume">
        <!-- Header Section -->
        <div class="header-section">
            <h1><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?></h1>
            <div class="contact-info">
                <p><?php echo htmlspecialchars($user['email']); ?></p>
                <?php if (!empty($user['job_title'])): ?>
                    <p><?php echo htmlspecialchars($user['job_title']); ?></p>
                <?php endif; ?>
            </div>
            <?php if (!empty($user['bio'])): ?>
                <p class="bio"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($social_profiles)): ?>
                <div class="social-links">
                    <?php foreach ($social_profiles as $platform => $url): ?>
                        <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="social-link">
                            <i class="fab fa-<?php echo $platform; ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Education Section -->
        <?php if (!empty($resume_data['education'])): ?>
        <div class="section">
            <h2 class="section-title">Education</h2>
            <?php foreach ($resume_data['education'] as $edu): ?>
                <div class="entry">
                    <div class="entry-title"><?php echo htmlspecialchars($edu['institution']); ?></div>
                    <div class="entry-subtitle"><?php echo htmlspecialchars($edu['degree']); ?></div>
                    <div class="date-range">
                        <?php echo $edu['start_date']; ?> - <?php echo $edu['end_date'] ?? 'Present'; ?>
                    </div>
                    <?php if (!empty($edu['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($edu['description'])); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Experience Section -->
        <?php if (!empty($resume_data['experience'])): ?>
        <div class="section">
            <h2 class="section-title">Work Experience</h2>
            <?php foreach ($resume_data['experience'] as $exp): ?>
                <div class="entry">
                    <div class="entry-title"><?php echo htmlspecialchars($exp['company']); ?></div>
                    <div class="entry-subtitle"><?php echo htmlspecialchars($exp['position']); ?></div>
                    <div class="date-range">
                        <?php echo $exp['start_date']; ?> - <?php echo $exp['end_date'] ?? 'Present'; ?>
                    </div>
                    <?php if (!empty($exp['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($exp['description'])); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Skills Section -->
        <?php if (!empty($resume_data['skills'])): ?>
        <div class="section">
            <h2 class="section-title">Skills</h2>
            <div class="skill-category">
                <?php foreach ($resume_data['skills'] as $skill): ?>
                    <span class="skill-item">
                        <?php echo htmlspecialchars($skill['skill_name']); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Projects Section -->
        <?php if (!empty($resume_data['projects'])): ?>
        <div class="section">
            <h2 class="section-title">Projects</h2>
            <?php foreach ($resume_data['projects'] as $project): ?>
                <div class="entry">
                    <div class="entry-title"><?php echo htmlspecialchars($project['title']); ?></div>
                    <?php if (!empty($project['role'])): ?>
                        <div class="entry-subtitle"><?php echo htmlspecialchars($project['role']); ?></div>
                    <?php endif; ?>
                    <div class="date-range">
                        <?php echo $project['start_date'] ?? ''; ?> 
                        <?php echo !empty($project['end_date']) ? ' - ' . $project['end_date'] : ''; ?>
                    </div>
                    <?php if (!empty($project['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Achievements Section -->
        <?php if (!empty($achievements)): ?>
        <div class="section">
            <h2 class="section-title">Achievements</h2>
            <?php foreach ($achievements as $achievement): ?>
                <div class="entry">
                    <div class="entry-title"><?php echo htmlspecialchars($achievement['title'] ?? 'Untitled Achievement'); ?></div>
                    <?php if (!empty($achievement['date'])): ?>
                        <div class="date-range"><?php echo htmlspecialchars($achievement['date'] ?? ''); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($achievement['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($achievement['description'] ?? '')); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Activities Section -->
        <?php if (!empty($activities)): ?>
        <div class="section">
            <h2 class="section-title">Activities</h2>
            <?php foreach ($activities as $activity): ?>
                <div class="entry">
                    <div class="entry-title"><?php echo htmlspecialchars($activity['activity_name'] ?? 'Untitled Activity'); ?></div>
                    <?php if (!empty($activity['date'])): ?>
                        <div class="date-range"><?php echo htmlspecialchars($activity['date'] ?? ''); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($activity['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($activity['description'] ?? '')); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>

    <div class="buttons-container">
        <button onclick="window.print()" class="action-button">
            <i class="fas fa-print"></i> Print
        </button>
        <button onclick="generatePDF()" class="action-button">
            <i class="fas fa-download"></i> Download PDF
        </button>
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('sideMenu');
            menu.classList.toggle('show');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('sideMenu');
            const hamburger = document.querySelector('.hamburger');
            if (!menu.contains(e.target) && !hamburger.contains(e.target) && menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        });

        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const element = document.getElementById('resume');
            
            // Hide buttons during capture
            const buttons = document.querySelector('.buttons-container');
            buttons.style.display = 'none';
            
            // Remove any transform scale that might affect rendering
            const originalTransform = element.style.transform;
            element.style.transform = 'none';
            
            html2canvas(element, {
                scale: 2, // Balanced scale for better performance
                useCORS: true,
                allowTaint: true,
                logging: false,
                letterRendering: true,
                foreignObjectRendering: true,
                scrollY: -window.scrollY,
                backgroundColor: '#1a1a1a', // Match background color
                onclone: function(clonedDoc) {
                    const clonedElement = clonedDoc.getElementById('resume');
                    clonedElement.style.padding = '20px';
                    clonedElement.style.margin = '0';
                    
                    // Ensure fonts are loaded in the cloned document
                    const style = clonedDoc.createElement('style');
                    style.textContent = `
                        @font-face {
                            font-family: 'Candara';
                            src: url('../assets/font/candara-light.ttf') format('truetype');
                            font-display: swap;
                        }
                    `;
                    clonedDoc.head.appendChild(style);
                }
            }).then(canvas => {
                // Restore original transform and buttons
                element.style.transform = originalTransform;
                buttons.style.display = 'flex';
                
                const imgData = canvas.toDataURL('image/png', 1.0); // Using PNG for better quality
                const pdf = new jsPDF({
                    orientation: 'p',
                    unit: 'mm',
                    format: 'a4',
                    compress: true
                });
                
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                const imgWidth = pageWidth;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                
                // Add image with white background
                pdf.setFillColor(26, 26, 26); // Match background color
                pdf.rect(0, 0, pageWidth, pageHeight, 'F');
                pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight, '', 'FAST');
                
                pdf.save('resume.pdf');
            });
        }
    </script>
</body>
</html>
