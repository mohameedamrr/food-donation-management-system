<?php
interface ICustomIterator
    {
    public function hasNext(): bool;

    // Get the next element
    public function next();

    // Remove the current element
    public function remove(): bool;
  }
?>