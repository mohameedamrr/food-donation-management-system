<?php
class DonationView {
    // Display donation item details
    public function showDonationItem($itemDetails) {
        echo '<h2>Donation Item Details</h2>';
        echo '<p>Item Name: ' . $itemDetails['name'] . '</p>';
        echo '<p>Weight: ' . $itemDetails['weight'] . ' kg</p>';
        echo '<img src="' . $itemDetails['image'] . '" alt="Item Image">';
        echo '<p>Status: ' . $itemDetails['status'] . '</p>';
    }

    // Display form to add a new donation
    public function showAddDonationForm() {
        echo '
        <form method="post" action="addDonation.php">
            <input type="text" name="itemName" placeholder="Item Name" required>
            <input type="number" name="itemWeight" placeholder="Item Weight (kg)" required>
            <input type="file" name="itemImage" required>
            <button type="submit">Add Donation</button>
        </form>';
    }

    // Display all donations
    public function showAllDonations($donations) {
        echo '<h2>All Donations</h2>';
        foreach ($donations as $donation) {
            echo '<div>';
            echo '<p>' . $donation['name'] . '</p>';
            echo '<a href="viewDonation.php?id=' . $donation['id'] . '">View Details</a>';
            echo '</div>';
        }
    }

    // Display donation status message
    public function showDonationStatus($status) {
        echo '<p>Donation Status: ' . $status . '</p>';
    }
}
?>
