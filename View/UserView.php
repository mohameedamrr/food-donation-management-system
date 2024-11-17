<?php
class UserView {
    // Display login form
    public function showLoginForm() {
        echo '
        <form method="post" action="login.php">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>';
    }

    // Display user profile
    public function showProfile($userData) {
        echo '<h2>User Profile</h2>';
        echo '<p>Name: ' . $userData['name'] . '</p>';
        echo '<p>Email: ' . $userData['email'] . '</p>';
        echo '<form method="post" action="updateProfile.php">
                <button type="submit">Update Profile</button>
              </form>';
    }

    // Display success message for login
    public function showLoginSuccess() {
        echo '<p>Login successful!</p>';
    }

    // Display logout message
    public function showLogoutSuccess() {
        echo '<p>You have logged out successfully!</p>';
    }
}
?>
