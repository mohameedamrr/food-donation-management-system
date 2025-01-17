<?php
class Donate implements IDeleteObject, IStoreObject, IReadObject {
    private int $donationID;
    private DateTime $donationDate;
    private string $userId;
    private DonateState $donationState;
    private bool $isDonationSuccessful;

    public function __construct(int $donationID, string $userId)
    {
        $this->donationID    = $donationID;
        $this->userId        = $userId;
        $this->donationDate  = new DateTime();
        $this->isDonationSuccessful = false;
        $this->donationState = new TotalCostState();
    }

    public function setNextState(DonateState $state): void
    {
        $this->donationState = $state;
    }

    public function proceedDonation(array $donationItems, IPayment $paymentMethod): void
    {

        $this->donationState->nextDonationState($this, $donationItems, $paymentMethod);
    }

    public function getDonationID(): int
    {
        return $this->donationID;
    }

    public function setDonationID(int $donationID): void
    {
        $this->donationID = $donationID;
    }

    public function getDonationDate(): DateTime
    {
        return $this->donationDate;
    }

    public function setDonationDate(DateTime $donationDate): void
    {
        $adminProxy = new DatabaseManagerProxy('admin');
        $date = $donationDate->format('Y-m-d H:i:s');
        $id = $this->donationID;
		$adminProxy->runQuery("UPDATE donations SET donation_date = '$date' WHERE donation_id = $id");
        $this->donationDate = $donationDate;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getDonationState(): DonateState
    {
        return $this->donationState;
    }

    public function getIsDonationSuccessful()
    {
        return $this->isDonationSuccessful;
    }

    public function setIsDonationSuccessful($isDonationSuccessful)
    {
        $adminProxy = new DatabaseManagerProxy('admin');
        $id = $this->donationID;
        if ($isDonationSuccessful) {
            $adminProxy->runQuery("UPDATE donations SET is_successful = TRUE WHERE donation_id = $id");
        } else {
            $adminProxy->runQuery("UPDATE donations SET is_successful = FALSE WHERE donation_id = $id");
        }
        $this->isDonationSuccessful = $isDonationSuccessful;
    }

    public static function storeObject(array $data) {
        $db = new DatabaseManagerProxy('donor');

        // Insert into donations table
        $donationDate = $data['donation_date']->format('Y-m-d H:i:s');
        $userId = $data['user_id'];
        $query = "INSERT INTO donations (donation_date, user_id, is_successful) VALUES ('$donationDate', $userId, FALSE)";
        $db->runQuery($query);

        // Get the last inserted ID
        $donationId = $db->getLastInsertId();

        return new self($donationId, $userId);
    }

    public static function deleteObject($id) {
        $db = new DatabaseManagerProxy('admin');

        // Delete from donation_history table
        $queryHistory = "DELETE FROM donation_history WHERE donation_id = $id";
        $db->runQuery($queryHistory);

        // Delete from donations table
        $query = "DELETE FROM donations WHERE donation_id = $id";
        $db->runQuery($query);

        return true;
    }

    public static function readObject($id) {
        $donorProxy = new DatabaseManagerProxy('donor');
        $row = $donorProxy->run_select_query("SELECT * FROM donations WHERE donation_id = $id")->fetch_assoc();
        if(isset($row)) {
            $donation = new self($row['donation_id'], $row['user_id']);
            $donation->donationDate = new DateTime($row['donation_date']);
            $donation->isDonationSuccessful = $row['is_successful'];
            if($donation->isDonationSuccessful) {
                $donation->donationState = new DonateCompletedState();
            } else {
                $donation->donationState = new DonateFailedState();
            }
        }
    }

}
