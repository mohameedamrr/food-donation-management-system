<?php
require_once "D:/WorkStation/Univeristy/food-donation-management-system/interfaces/Ilogin.php";
require_once "GoogleMethod.php";
abstract class UserEntity {
    private $id;
    private $name;
    private $email;
    private $phone;
    private $password;
    private $loginMethod;

    public function __construct($id, $name, $email, $phone, $password, $loginMethod) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->loginMethod = $loginMethod;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getLoginMethod() {
        return $this->loginMethod;
    }

    public function setLoginMethod($loginMethod) {
        $this->loginMethod = $loginMethod;
    }

    public function login(): bool {
        return $this->loginMethod->authenticate($this->email, $this->password);
    }

    public function logout(&$reference): bool {
        $reference = null;
        return true;
    }

    public function updateProfile(array $profileData): void {
        foreach($profileData as $pd => $value) {
            if (property_exists($this, $pd)) {
                $this->{$pd} = $value;
            }
        }
    }
}
?>
