<?php

require ("DonationItem.php");
$donationItem = new DonationItem();

$donationItem->getDonationItemInstance(1);
echo $donationItem->getItemDetails();

?>