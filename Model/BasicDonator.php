<?php
require_once 'UserEntity.php';
require_once 'Location.php';
require_once 'Donate.php';
require_once 'NormalMethod.php';

class BasicDonator extends UserEntity implements ICRUD{
    private $location;
    private $donationHistory; // array of Donate

    public function __construct($id, $location) {
        $sql = "select * from users where id = $id";
        $db = DatabaseManager::getInstance();
        $row = $db->runQuery($sql);
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
        }
        $this->location = $location;
    }

    public static function storeObject(array $data): ICRUD {
        // Collect the keys and values from the data array
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data)); // Prepare placeholders for binding

        // Prepare the SQL insert statement
        $sql = "INSERT INTO users ($columns) VALUES ($placeholders)";

        // Get the DatabaseManager instance
        $db = DatabaseManager::getInstance();

        // Bind and execute the query
        $db->runQuery($sql, $data);

        // Fetch and return the inserted object if needed
        $lastInsertedId = $db->getLastInsertId();
        return new BasicDonator($lastInsertedId, null);
    }

    public static function readObject($id): void {
        $sql = "select * from users where id = $id";
        $db = DatabaseManager::getInstance();
        $row = $db->runQuery($sql);
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
?>
