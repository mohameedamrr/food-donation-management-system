<!-- views/login_form.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        /* Add your styles */
    </style>
</head>
<body>
    <h1>Login</h1>
    <?php if (!empty($errorMessage)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>
    <form action="index.php?action=login" method="POST">
        <label>Email:</label>
        <input type="email" name="username" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="index.php?action=register">Register here</a></p>
</body>
</html>