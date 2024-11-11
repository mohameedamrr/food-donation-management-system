<?php
require_once __DIR__ . '/../interfaces/ILogin.php';

class NormalMethod implements ILogin {
    private $hashedPassword;

    public function __construct($hashedPassword) {
        $this->hashedPassword = $hashedPassword;
    }

    public function authenticate($username, $password) {
        // Assume user data is retrieved from a database
        // For simplicity, we check the hashed password
        return password_verify($password, $this->hashedPassword);
    }
}
?>
