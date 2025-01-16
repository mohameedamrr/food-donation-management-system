<?php
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
abstract class UserEntity {
    protected $id;
    protected $name;
    protected $email;
    protected $phone;
    protected $password;

    public function __construct($id, $name, $email, $phone, $password) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        $sql = "UPDATE `food_donation`.`users` SET id = $this->id WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        $sql = "UPDATE `food_donation`.`users` SET name = '$this->name' WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        $sql = "UPDATE `food_donation`.`users` SET email = '$this->email' WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
        $sql = "UPDATE `food_donation`.`users` SET phone = '$this->phone' WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        $sql = "UPDATE `food_donation`.`users` SET password = '$this->password' WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
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
