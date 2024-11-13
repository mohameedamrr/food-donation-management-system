<?php
interface ICRUD {
    public static function storeObject(array $data);
    public static function readObject($id);
    public function updateObject(array $data);
    public static function deleteObject($id);
}
?>