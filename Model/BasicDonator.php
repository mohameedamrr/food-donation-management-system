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
    private $location;
    private $donationHistory; // array of Donate

    public function __construct($id, $location) {
        $sql = "SELECT * FROM `food_donation`.`users` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
        }
        $this->location = $location;
    }

    public static function storeObject(array $data) {
        // Collect the keys from the data array for column names
        $columns = implode(", ", array_map(fn($key) => "`$key`", array_keys($data)));
        
        // Prepare placeholders for the values (all as ? for binding)
        $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
        $sql = "INSERT INTO `food_donation`.`users` ($columns) VALUES ($placeholders)";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
        $lastInsertedId = $db->getLastInsertId();
        echo $lastInsertedId;
        return new BasicDonator($lastInsertedId, null);
    }

    public static function readObject($id): void {
        $sql = "select * from `food_donation`.`users` where id = $id";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql);
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
        }
    }

    public function updateObject(array $data): void {
        foreach($data as $d => $value) {
            if (property_exists($this, $d)) {
                $this->{$d} = $value;
            }
        }
    }

    public static function deleteObject($id): void {
        
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

    public function getLocation(): Location {
        return $this->location;
    }

    public function setLocation($location): void {
        $this->location = $location;
    }
}
$u = BasicDonator::storeObject(array("id"=>"33", "name"=>"Etshs", "email" => "a344@dds", "phone" => "aaaa+20123123", "password" => "qweq"));
echo $u->getName();
?>
