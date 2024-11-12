<?php
// View/UserView.php
spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        'View/',
        '../interfaces/',
    ];
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
class UserView {

    public function displayLoginSuccess(UserEntity $user) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login Successful</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                nav a { margin: 0 10px; text-decoration: none; color: #333; }
                main { padding: 20px; }
            </style>
        </head>
        <body>
            <header>
                <h1>Welcome, <?php echo htmlspecialchars($user->getName()); ?></h1>
                <nav>
                    <a href="profile.php">Profile</a> |
                    <a href="schedule_appointment.php">Schedule Appointment</a> |
                    <a href="make_donation.php">Make Donation</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <p>You have logged in successfully.</p>
                <p><a href="profile.php">Go to your profile</a></p>
            </main>

            <footer>
                <p>&copy; 2024 User Management</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayLoginFailure() {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login Failed</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; color: red; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                main { padding: 20px; }
            </style>
        </head>
        <body>
            <header>
                <h1>Login Failed</h1>
            </header>

            <main>
                <p>Incorrect username or password. Please try again.</p>
                <p><a href="login.php">Back to Login</a></p>
            </main>

            <footer>
                <p>&copy; 2024 User Management</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayLogoutSuccess() {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Logout Successful</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                main { padding: 20px; }
            </style>
        </head>
        <body>
            <header>
                <h1>Logout Successful</h1>
            </header>

            <main>
                <p>You have been logged out successfully. See you next time!</p>
                <p><a href="login.php">Login Again</a></p>
            </main>

            <footer>
                <p>&copy; 2024 User Management</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayRegistrationSuccess(UserEntity $user) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Registration Successful</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                main { padding: 20px; }
            </style>
        </head>
        <body>
            <header>
                <h1>Welcome, <?php echo htmlspecialchars($user->getName()); ?></h1>
            </header>

            <main>
                <p>Your account has been created successfully.</p>
                <p><a href="profile.php">Go to your profile</a></p>
            </main>

            <footer>
                <p>&copy; 2024 User Management</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayProfileUpdateSuccess(UserEntity $user) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Profile Updated</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                main { padding: 20px; }
            </style>
        </head>
        <body>
            <header>
                <h1>Profile Updated</h1>
                <nav>
                    <a href="profile.php">Profile</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <p>Your profile has been updated successfully.</p>
                <p><a href="profile.php">Back to Profile</a></p>
            </main>

            <footer>
                <p>&copy; 2024 User Management</p>
            </footer>
        </body>
        </html>
        <?php
    }

    public function displayUserProfile(UserEntity $user) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>User Profile</title>
            <style>
                /* Add your CSS styles here */
                body { font-family: Arial, sans-serif; }
                header, footer { background-color: #f8f8f8; padding: 10px; text-align: center; }
                nav a { margin: 0 10px; text-decoration: none; color: #333; }
                main { padding: 20px; }
                p { margin-bottom: 10px; }
            </style>
        </head>
        <body>
            <header>
                <h1>User Profile</h1>
                <nav>
                    <a href="index.php">Home</a> |
                    <a href="schedule_appointment.php">Schedule Appointment</a> |
                    <a href="make_donation.php">Make Donation</a> |
                    <a href="logout.php">Logout</a>
                </nav>
            </header>

            <main>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user->getName()); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user->getEmail()); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user->getPhone()); ?></p>
                <!-- Add more profile details as needed -->
                <p><a href="edit_profile.php">Edit Profile</a></p>
            </main>

            <footer>
                <p>&copy; 2024 User Management</p>
            </footer>
        </body>
        </html>
        <?php
    }
}
?>
