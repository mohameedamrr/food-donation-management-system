<?php
require_once __DIR__ . '/../interfaces/ILogin.php';
require_once __DIR__ . '/../interfaces/Observer.php';

abstract class UserEntity implements Observer {
    public $id;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $donations; // List of Donate objects
    public $loginMethod; // ILogin

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
    public function getId() {
		return $this->id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($value) {
		$this->name = $value;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($value) {
		$this->email = $value;
	}

	public function getPhone() {
		return $this->phone;
	}

	public function setPhone($value) {
		$this->phone = $value;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($value) {
		$this->password = $value;
	}

	public function setDonations($value) {
		$this->donations = $value;
	}

	public function getLoginMethod() {
		return $this->loginMethod;
	}

	public function setLoginMethod($value) {
		$this->loginMethod = $value;
	}

}
?>


