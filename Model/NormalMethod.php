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

require_once 'ProxyDP/DatabaseManagerProxy.php';

class NormalMethod implements ILogin {
    private $hashedPassword;

    public function authenticate(string $email, string $password): bool {
        // Prepare the SQL query to select user by email
        $sql = "SELECT * FROM `food_donation`.`users` WHERE email = '$email'";
        $db = new DatabaseManagerProxy('admin');
        $row=$db->run_select_query($sql)->fetch_assoc();
        $hashedPassword = md5($password);
        echo $row["password"].'<br>';
        //echo $hashedPassword.'<br>';
        if($row["password"] == $hashedPassword){
            return true;
        }
        
        return false;
    }
}

// $normal = new NormalMethod();
// echo $normal->authenticate('bertha.wilkinson@example.com','b95925ed0aa3897a613c7534ae7abeef')? "Login Successfully": "Failed to Login";
?>
