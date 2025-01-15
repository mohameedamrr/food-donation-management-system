<?php
class ChangeDonationDescriptionCommand implements ICommand {
    private $employee;
    private $donationDetails;
    private $newDescription;
    private $previousDescription;

    public function __construct(DonationDetails $donationDetails,string $newDescription, string $previousDescription) {
        $this->donationDetails = $donationDetails;
        $this->newDescription = $newDescription;
        $this->previousDescription = $previousDescription;
        $donorProxy = new DatabaseManagerProxy('admin');
        $row = $donorProxy->run_select_query("SELECT * FROM employees WHERE 'role' = 'Manager' ")->fetch_assoc();
        $user_id = $row['id'];
        $row2 = $donorProxy->run_select_query("SELECT * FROM users WHERE id = $user_id ")->fetch_assoc();
        $this->employee = new Employee($row2['email']);
    }
    public function execute(): void{
        $this->employee->changeDonationDescription($this->donationDetails, $this->newDescription);
    }
    public function undo(): void{
        $this->employee->changeDonationDescription($this->donationDetails, $this->previousDescription);
    }

    public function getDonationDetails()
    {
        return $this->donationDetails;
    }

    public function setDonationDetails($donationDetails)
    {
        $this->donationDetails = $donationDetails;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setEmployee($employee)
    {
        $this->employee = $employee;
    }

    public function getNewDescription()
    {
        return $this->newDescription;
    }

    public function setNewDescription($newDescription)
    {
        $this->newDescription = $newDescription;


    }
    public function getPreviousDescription()
    {
        return $this->previousDescription;
    }

    public function setPreviousDescription($previousDescription)
    {
        $this->previousDescription = $previousDescription;


    }
}
?>