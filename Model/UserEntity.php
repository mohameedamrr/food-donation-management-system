<?php
abstract class UserEntity {
    protected $id;
    protected $name;
    protected $email;
    protected $phone;
    protected $password;
    protected $donations; // array of Donate objects
    protected $loginMethod; // ILogin

    public function login(string $username, string $password): bool {
        // Authenticate the user
    }

    public function logout(): void {
        // Log out the user
    }

    public function updateProfile(array $profileData): void {
        // Update the user profile with provided data
    }
}
?>
