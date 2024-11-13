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

class NormalMethod implements ILogin {
    private $hashedPassword;

    public function authenticate(string $email, string $password): bool {
        // Prepare the SQL query to select user by email
        $sql = "SELECT * FROM `food_donation`.`users` WHERE email = '$email'";
        $db = DatabaseManager::getInstance();
        $row=$db->run_select_query($sql)->fetch_assoc();
        if($row["password"] == $password){
            return true;
        }
        
        return false;
    }
}
?>
