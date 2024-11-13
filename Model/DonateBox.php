<?php
require_once 'BillableDonate.php';

abstract class DonateBox extends BillableDonate {


    public abstract function getContentDetails(): string ;


}
?>
