<?php
interface IDeleteObject {
    public static function deleteObject($id);

}

// public static function deleteObject($id) {
//     $sql = "DELETE FROM food_donation.users WHERE id = $id";
//     $db = DatabaseManager::getInstance();
//     $db->runQuery($sql);
// }
?>