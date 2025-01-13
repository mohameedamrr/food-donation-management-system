<?php
interface IReadObject {
    public static function readObject($id);

}
// public static function readObject($id) {
//     return new BasicDonator($id, null);
// }
// public function construct($id) {
//     $sql = "SELECT * FROM food_donation.users WHERE id = $id";
//     $db = DatabaseManager::getInstance();
//     $row = $db->run_select_query($sql)->fetch_assoc();
//     if(isset($row)) {
//         parent::construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
//     }
// }
?>