<?php
// spl_autoload_register(function ($class_name) {
//     $directories = [
//         '../Model/',
//         '../Controller/',
//         '../View/',
//         '../interfaces/',
//     ];
//     foreach ($directories as $directory) {
//         $file = __DIR__ . '/' . $directory . $class_name . '.php';
//         if (file_exists($file)) {
//             require_once $file;
//             return;
//         }
//     }
// });

// require_once 'ProxyDP/DatabaseManagerProxy.php';

class NormalMethod implements ILogin {
    private $hashedPassword;

    public function authenticate(string $email, string $password, string $type): UserEntity|NULL {
        // Prepare the SQL query to select user by email
        $sql = "SELECT * FROM `food_donation`.`users` WHERE email = '$email'";
        $db = new DatabaseManagerProxy('admin');
        $row = $db->run_select_query($sql)->fetch_assoc();
        $hashedPassword = md5($password);
        // echo $row["password"].'<br>';
        //echo $hashedPassword.'<br>';
        if ($row && $row["password"] == $hashedPassword) {
            if ($type == "Donator"){
                return new BasicDonator($email);
            }
            elseif ($type == "Employee"){
                $user_id = $row['id'];
                $sql2 = "SELECT * FROM `food_donation`.`employees` WHERE id = $user_id";
                $row2 = $db->run_select_query($sql2)->fetch_assoc();
                if(isset($row2)) {
                    return new Employee($email);
                } else {
                    return null;
                }
            }
            elseif ($type == "Admin"){
                return new Admin(1);
            }
        }
        
        return NULL;
    }
}

// $normal = new NormalMethod();
// echo $normal->authenticate('bertha.wilkinson@example.com','b95925ed0aa3897a613c7534ae7abeef')? "Login Successfully": "Failed to Login";
?>
