<?php
interface IUpdateObject {
    public function updateObject(array $data);

}

// public function updateObject(array $data) {
//     $updates = [];
//     foreach ($data as $prop => $value) {
//         $this->{$prop} = $value;
//         $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
//         $updates[] = "$prop = $value";
//     }
//     $sql = "UPDATE food_donation.users SET " . implode(", ", $updates) . " WHERE id = $this->id";
//     DatabaseManager::getInstance()->runQuery($sql);
// }
?>