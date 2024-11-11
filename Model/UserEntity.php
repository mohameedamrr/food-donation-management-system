<?php
require_once __DIR__ . '/../interfaces/ILogin.php';
require_once __DIR__ . '/../interfaces/Observer.php';

abstract class UserEntity implements Observer {
    protected $id;
    protected $name;
    protected $email;
    protected $phone;
    protected $password;
    protected $donations; // List of Donate objects
    protected $loginMethod; // ILogin

    public function __construct($id, $name, $email, $phone, $password, ILogin $loginMethod) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->donations = array();
        $this->loginMethod = $loginMethod;
    }

    public function login($username, $password) {
        return $this->loginMethod->authenticate($username, $password);
    }

    public function logout() {
        // Logout logic (e.g., session destruction)
        session_start();
        session_destroy();
    }

    public function updateProfile($profileData) {
        // Update user profile with provided data
        foreach ($profileData as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function update($subject) {
        // Handle notifications from observed subjects
        // For example, add notification to user's notifications list
    }

    public function addDonation(Donate $donation) {
        $this->donations[] = $donation;
    }

    public function getDonations() {
        return $this->donations;
    }
}
?>
