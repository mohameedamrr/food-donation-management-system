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

class BasicDonator extends UserEntity implements ICRUD{
    private $donationHistory; // array of Donate

    public function __construct($id) {
        $sql = "SELECT * FROM `food_donation`.`users` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
        }
    }

    public static function storeObject(array $data) {
        $hashedPassword = md5($data['password']);
        $data['password'] = $hashedPassword;
        $columns = implode(", ", array_map(fn($key) => "`$key`", array_keys($data)));
        $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
        $sql = "INSERT INTO `food_donation`.`users` ($columns) VALUES ($placeholders)";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
        $lastInsertedId = $db->getLastInsertId();
        return new BasicDonator($lastInsertedId, null);
    }

    public static function readObject($id) {
        // $sql = "SELECT * FROM `food_donation`.`users` WHERE id = $id";
        // $db = DatabaseManager::getInstance();
        // $row = $db->run_select_query($sql)->fetch_assoc();
        // if(isset($row)) {
        //     return new BasicDonator($row["id"], null);
        // }
        // return null;
        return new BasicDonator($id, null);
    }

    public function updateObject(array $data) {
        $updates = [];
        foreach ($data as $prop => $value) {
            $this->{$prop} = $value;
            $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $updates[] = "`$prop` = $value";
        }
        $sql = "UPDATE `food_donation`.`users` SET " . implode(", ", $updates) . " WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }


    public static function deleteObject($id) {
        $sql = "DELETE FROM `food_donation`.`users` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
    }

    public function makeDonation(array $items, int $id, Donate $donation): bool {
        array_push($this->donationHistory, $donation);
        return $donation->donate($items, $id);
    }

    public function getDonationHistory(): array {
        return $this->donationHistory;
    }

    public function setDonationHistory($donationHistory): void {
        $this->donationHistory = $donationHistory;
    }
}
?>
