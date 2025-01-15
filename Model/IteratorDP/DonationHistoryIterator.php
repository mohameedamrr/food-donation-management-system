<?php
class DonationHistoryIterator implements ICustomIterator {
    private $donations = [];
    private $position = 0;

    public function __construct($donations) {
    $this->donations = $donations;
    }

    // Check if there is a next element
    public function hasNext(): bool {
    return isset($this->donations[$this->position]);
    }

    // Get the next element
    public function next() {
    if ($this->hasNext()) {
    return $this->donations[$this->position++];
    }
    return null;
    }

    // Remove the current element
    public function remove(): bool {
    if (isset($this->donations[$this->position - 1])) {
    unset($this->donations[$this->position - 1]);
    // Re-index the array after removal
    $this->donations = array_values($this->donations);
    $this->position--; // Adjust the position after removal
    return true;
    }
    return false;
    }
}