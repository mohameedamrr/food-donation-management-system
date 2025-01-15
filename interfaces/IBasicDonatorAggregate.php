<?php

interface IBasicDonatorAggregate
{
    public function createIterator(): ICustomIterator;
}
?>