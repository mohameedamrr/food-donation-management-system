<?php
interface IDatabaseManager {
    public function runQuery($query): mysqli_result|bool;
    public function run_select_query($query): mysqli_result|bool;
    public function getLastInsertId(): int;
}
?>