<?php
require_once "D:/WorkStation/Univeristy/food-donation-management-system/interfaces/Ilogin.php";

class GoogleMethod implements ILogin {
    private $googleToken;

    public function authenticate(string $username, string $password): string {
        return "Authenticated Successfully";
    }
}
?>
