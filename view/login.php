<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/portfolio_b/assets/css/login.css?v=<?php echo time(); ?>">
</head>
<body>
    <form id="login-form" method="post" action="../actions/login_handler.php">
        <h1>Login</h1>
        
        <!-- Display the error message if any -->
        <?php if (isset($_SESSION['login_error'])): ?>
            <p style="color:red;"><?php echo $_SESSION['login_error']; ?></p>
            <?php unset($_SESSION['login_error']); // Clear the error after displaying it ?>
        <?php endif; ?>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
        <p>Don't have an account? <a href="../view/signup.php">Sign up here</a></p>
    </form>

    <video autoplay muted loop id="myVideo">
            <source src="../assets/images/BG3.mp4" type="video/mp4">
    </video>

    <script>
        const form = document.getElementById('login-form');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        form.addEventListener('submit', function(event) {
            let valid = true;
            emailError.textContent = '';
            passwordError.textContent = '';

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
