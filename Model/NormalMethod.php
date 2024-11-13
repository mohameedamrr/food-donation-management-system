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

    public function authenticate(string $username, string $password): string {
        $sql = "select * from users where username = $username and password = $password";
        $db = DatabaseManager::getInstance();
        $row = $db->runQuery($sql);
        if($row->num_rows) {
            return true;
        }
        else {
            return false;
        }
    }
}
?>
