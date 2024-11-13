<?php
require_once 'ILogin.php';

class GoogleMethod implements ILogin {
    private $googleToken;

    public function authenticate(string $username, string $password): bool {
        // Authenticate using Google OAuth
    }
}
?>
