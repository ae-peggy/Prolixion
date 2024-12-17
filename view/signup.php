<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="/portfolio_b/assets/css/login.css?v=<?php echo time(); ?>">
</head>
<body>
    <form id="signup-form" method="post" action="../actions/signuphandler.php">
        <h1>✺Welcome To Prolixion✺</h1>
        <h2>Sign Up</h2>

        <!-- Show errors -->
        <?php if (isset($_SESSION['errors'])): ?>
            <div class="errors">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['errors']); ?>
            </div>
        <?php endif; ?>

        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname" required><br><br>
        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lname" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Sign Up</button>
        <p>Already have an account? <a href="..assets/images//view/login.php">Login here</a></p>
    </form>
    

    <video autoplay muted loop id="myVideo">
        <source src="../assets/images/BG3.mp4" type="video/mp4">
    </video>

    <script>
        const form = document.getElementById('signup-form');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        form.addEventListener('submit', function(event) {
            let valid = true;
            emailError.textContent = '';
            passwordError.textContent = '';

            // Email validation
            const emailPattern = /^[^\\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                emailError.textContent = 'Please enter a valid email address.';
                valid = false;
            }

            // Password validation
            const passwordPattern = /^(?=.*[A-Z])(?=.*\d{3,})(?=.*[@#$%^&+=!]).{8,}$/;
            if (!passwordPattern.test(password.value)) {
                passwordError.textContent = 'Password must be at least 8 characters, include 1 uppercase letter, 3 digits, and 1 special character.';
                valid = false;
            }

            // Prevent form submission if validation fails
            if (!valid) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
