<?php
require_once 'Donate.php';

abstract class NonBillableDonate extends Donate {
    protected $expiryDate;
    protected $itemImage;
    protected $weight;

    public function __construct($donationID, $donationDate, UserEntity $user, $expiryDate, $itemImage, $weight) {
        parent::__construct($donationID, $donationDate, $user);
        $this->expiryDate = $expiryDate;
        $this->itemImage = $itemImage;
        $this->weight = $weight;
    }

    public function addImage($image) {
        $this->itemImage = $image;
    }

    public function checkItems() {
        // Check if items are acceptable (e.g., not expired)
        return $this->expiryDate > new DateTime();
    }
}
?>
