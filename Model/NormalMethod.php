<?php
require_once "D:/WorkStation/Univeristy/food-donation-management-system/interfaces/Ilogin.php";
require_once 'DatabaseManager.php';

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
