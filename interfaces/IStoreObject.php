<?php
interface IStoreObject {
    public static function storeObject(array $data);

}

// public static function storeObject(array $data) {
//     $columns = implode(", ", array_map(fn($key) => "$key", array_keys($data)));
//     $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
//     $sql = "INSERT INTO food_donation.users ($columns) VALUES ($placeholders)";
//     $db = DatabaseManager::getInstance();
//     $db->runQuery($sql);
//     $lastInsertedId = $db->getLastInsertId();
//     return new BasicDonator($lastInsertedId, null);
// }
?>
