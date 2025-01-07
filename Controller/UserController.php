<?php
require_once '../Model/UserEntity.php';
require_once '../Model/NormalMethod.php';
require_once '../Model/DatabaseManager.php';

class UserController {
    private $user;

    public function __construct(UserEntity $user) {
        $this->user = $user;
    }

    // User Login
    public function login($email, $password) {
        $this->user->setEmail($email);
        $this->user->setPassword($password);
        if ($this->user->login()) {
            echo "Login successful.";
            return true;
        } else {
            echo "Invalid credentials.";
            return false;
        }
    }

    // User Logout
    public function logout() {
        $this->user->logout($this->user);
        echo "Logout successful.";
    }

    // Update User Profile
    public function updateProfile($profileData) {
        $this->user->updateProfile($profileData);
        echo "Profile updated successfully.";
    }
}
?>
