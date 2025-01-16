<?php

require_once 'DonateState.php';

class storeDonationDetailsState extends DonateState
{
    private DonationDetails $donationDetails;

    public function __construct(DonationDetails $donationDetails)
    {
        $this->donationDetails = $donationDetails;
    }

    /**
     * 4 - Store donation details entry in DB & donate
     */
    public function nextDonationState(Donate $donate, array $donationItems, IPayment $paymentMethod): void
    {
        // Save $donationDetails to DB
        // Using a DatabaseManagerProxy or direct DatabaseManager (depending on your design)

        $dbProxy = new DatabaseManagerProxy("Admin"); // or pass role from session

        // For example, an INSERT query:
        $query = "
            INSERT INTO donation_details (donation_id, total_cost, description)
            VALUES (
                " . (int) $this->donationDetails->DonationId . ",
                " . (float) $this->donationDetails->totalCost . ",
                '" . $dbProxy->escapeString($this->donationDetails->description) . "'
            )
        ";
        
        $result = $dbProxy->runQuery($query);

        if (!$result) {
            // If storing fails, transition to failedState
            $donate->setNextState(new failedState("Failed to store donation details in DB."));
            return;
        }

        // Possibly store donation items too, or handle in a loop

        // If successful, transition to sending mail
        $donate->setNextState(new sendMailState($this->donationDetails));
    }
}
