<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        /* Add your styles */
    </style>
</head>
<body>
    <h1>Register</h1>
    <?php if (!empty($errorMessage)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>
    <form action="index.php?action=register" method="POST">
        <label>Name:</label>
        <input type="text" name="name" required><br><br>
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        <label>Phone:</label>
        <input type="text" name="phone" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="index.php?action=login">Login here</a></p>
</body>
</html>