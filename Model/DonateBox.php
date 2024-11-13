<?php
require_once 'BillableDonate.php';

abstract class DonateBox extends BillableDonate {
    public function getContentDetails(): array {
        // Return content details of the box
    }
}
?>
