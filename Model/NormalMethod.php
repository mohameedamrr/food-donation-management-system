<?php
require_once 'ILogin.php';

class NormalMethod implements ILogin {
    private $hashedPassword;

    public function authenticate(string $username, string $password): bool {
        // Authenticate using normal method
    }
}
?>
