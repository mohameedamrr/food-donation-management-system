<?php
// controllers/UserController.php

spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        '../View/',
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
class UserController {
    private $userView;

    public function __construct() {
        $this->userView = new UserView();
    }

    public function displayLoginForm($errorMessage = '') {
        include __DIR__ . '/../View/login_form.php';
    }

    public function displayRegistrationForm($errorMessage = '') {
        include __DIR__ . '/../View/registration_form.php';
    }

    public function login($username, $password) {
        $user = $this->authenticateUser($username, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            $this->userView->displayLoginSuccess($user);
        } else {
            $this->userView->displayLoginFailure();
        }
    }

    public function logout() {
        session_destroy();
        $this->userView->displayLogoutSuccess();
    }

    public function register($userData) {
        $loginMethod = new NormalMethod(password_hash($userData['password'], PASSWORD_DEFAULT));
        $user = new BasicUser(
            rand(1, 1000), // Random ID for demonstration
            $userData['name'],
            $userData['email'],
            $userData['phone'],
            $userData['password'],
            $loginMethod
        );
        // Save user to data store (e.g., database)
        $_SESSION['user'] = $user;
        $this->userView->displayRegistrationSuccess($user);
    }

    public function viewProfile($user) {
        if ($user) {
            $this->userView->displayUserProfile($user);
        } else {
            header('Location: index.php?action=login');
        }
    }

    public function updateProfile($user, $profileData) {
        $user->setPhone($profileData['phone'] ?? $user->getPhone());
        // Save changes to data store
        $this->userView->displayProfileUpdateSuccess($user);
    }

    private function authenticateUser($username, $password) {
        // Authentication logic
        // For demonstration purposes, accept any password
        if ($password === 'password123') {
            return new BasicUser(
                rand(1, 1000),
                'John Doe',
                $username,
                '1234567890',
                'password123',
                new NormalMethod(password_hash('password123', PASSWORD_DEFAULT))
            );
        }
        return null;
    }
}
?>