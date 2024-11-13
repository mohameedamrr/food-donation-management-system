<?php
interface ILogin {
    public function authenticate(string $username, string $password): bool;
}
?>
