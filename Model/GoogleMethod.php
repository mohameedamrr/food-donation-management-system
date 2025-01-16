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

class GoogleMethod implements ILogin {
    private $googleToken;

    public function authenticate(string $email, string $password, string $type): UserEntity|NULL {
        // Check if the email contains "@gmail.com"
        if (!preg_match('/@gmail\.com$/i', $email)) {
            return NULL; // Login fails if the email is not a Gmail address
        }

        // Prepare the SQL query to select user by email
        $sql = "SELECT * FROM `food_donation`.`users` WHERE email = '$email'";
        $db = new DatabaseManagerProxy('admin');
        $row = $db->run_select_query($sql)->fetch_assoc();

        $hashedPassword = md5($password);

        // Check if the user exists and the password matches
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
        return NULL; // Login fails if the user doesn't exist or the password is incorrect
    }
}
?>