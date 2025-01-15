<?php
abstract class DonationFactory {

    public function createAndValidate(String $type, array $details): DonationItem | bool {
        $donationItem = $this->createDonationItem($type, $details);
        if($donationItem->validate()) {
            return $donationItem;
        } else {
            return false;
        }
    }

    protected abstract function createDonationItem(String $type, array $details): DonationItem;

}
?>
