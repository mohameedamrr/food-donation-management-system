<?php
interface ICRUD {
    public static function storeObject(array $data): ICRUD;
    public static function readObject($id): void;
    public function updateObject(array $data): void;
    public static function deleteObject($id): void;
}
?>