<?php
require_once 'db/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Builder</title>
    <link rel="stylesheet" href="/portfolio_b/assets/css/index.css?v=<?php echo time(); ?>">
</head>
<body>
    <video autoplay muted loop id="myVideo">
        <source src="/portfolio_b/assets/images/BG3.mp4" type="video/mp4">
    </video>
    <div class="frosted-overlay"></div>
    <div class="container">
        <header>
            <h1>Prolixion Portfolio Builder</h1>
        </header>
        
        <main>
            <section class="project-overview">
                <h2>Welcome to Your Professional Portfolio Platform</h2>
                
                <div class="feature-grid">
                    <div class="feature-card">
                        <h3>Create Your Story</h3>
                        <p>Craft a compelling professional narrative that showcases your unique skills and experiences.</p>
                    </div>
                    
                    <div class="feature-card">
                        <h3>Dynamic Resume Builder</h3>
                        <p>Easily design and customize your resume with our intuitive, professional templates.</p>
                    </div>
                    
                    <div class="feature-card">
                        <h3>Feedback & Improvement</h3>
                        <p>Get professional insights and recommendations to enhance your portfolio.</p>
                    </div>
                </div>
                
                <div class="cta-section">
                    <a href="/portfolio_b/view/login.php" class="btn btn-primary">Get Started</a>
                    <a href="/portfolio_b/view/signup.php" class="btn btn-secondary">Create Account</a>
                </div>
            </section>
        </main>
        <footer>
            <p>&copy; <?php echo date('Y'); ?> Portfolio Builder. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
