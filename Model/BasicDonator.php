<?php
spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        '../View/',
        '../interfaces/',
    ];
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

class BasicDonator extends UserEntity implements IStoreObject, IUpdateObject, IDeleteObject, IBasicDonatorAggregate {
    private $donationHistory; // array of Donation details
    private $location;
    private $appointments;

    public function __construct($email, $loginMethod = new NormalMethod()) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $db = new DatabaseManagerProxy('donor');
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], $loginMethod);
            $this->donationHistory = $this->fetchDonationHistory($row["id"]);
            $this->appointments = $this->fetchAppointments($row["id"]);
        }
    }

    private function fetchDonationHistory($donorId): array {
        $donationHistory = [];


        $sql = "SELECT donation_id FROM donations WHERE user_id = $donorId";
        $db = new DatabaseManagerProxy('donor');
        $donationIds = $db->run_select_query($sql)->fetch_all(MYSQLI_ASSOC);


        foreach ($donationIds as $donationIdRow) {
            $donationId = $donationIdRow['donation_id'];
            $sql = "SELECT * FROM donation_history WHERE donation_id = $donationId";
            $donationDetailsRows = $db->run_select_query($sql)->fetch_all(MYSQLI_ASSOC);


            foreach ($donationDetailsRows as $donationDetailsRow) {
                $donationDetails = new DonationDetails($donationDetailsRow['id']);
                $donationHistory[] = $donationDetails;
            }
        }

        return $donationHistory;
    }

    private function fetchAppointments($userId): array {
        $appointments = [];

        $sql = "SELECT * FROM appointments WHERE userID = $userId";
        $db = new DatabaseManagerProxy('donor');
        $appointmentRows = $db->run_select_query($sql)->fetch_all(MYSQLI_ASSOC);

        foreach ($appointmentRows as $appointmentRow) {
            $appointment = new Appointment($appointmentRow['appointmentID']);
            $appointments[] = $appointment;
        }

        return $appointments;
    }
    public static function storeObject(array $data, $loginMethod = new NormalMethod()) {
        $hashedPassword = md5($data['password']);
        $data['password'] = $hashedPassword;
        $columns = implode(", ", array_map(fn($key) => "`$key`", array_keys($data)));
        $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
        $sql = "INSERT INTO users ($columns) VALUES ($placeholders)";
        $db = new DatabaseManagerProxy('donor');
        $db->runQuery($sql);
        $lastInsertedId = $db->getLastInsertId();
        return new BasicDonator($lastInsertedId, $loginMethod);
    }

    // public static function readObject($id) {
    //     // $sql = "SELECT * FROM `food_donation`.`users` WHERE id = $id";
    //     // $db = DatabaseManager::getInstance();
    //     // $row = $db->run_select_query($sql)->fetch_assoc();
    //     // if(isset($row)) {
    //     //     return new BasicDonator($row["id"], null);
    //     // }
    //     // return null;
    //     return new BasicDonator($id, null);
    // }

    public function updateObject(array $data) {
        $updates = [];
        foreach ($data as $prop => $value) {
            $this->{$prop} = $value;
            $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $updates[] = "`$prop` = $value";
        }
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = $this->id";
        $db = new DatabaseManagerProxy('donor');
        $db->runQuery($sql);
    }


    public static function deleteObject($id) {
        $sql = "DELETE FROM users WHERE id = $id";
        $db = new DatabaseManagerProxy('donor');
        $db->runQuery($sql);
    }

    public function makeDonation(array $items, int $id, Donate $donation): bool {
        array_push($this->donationHistory, $donation);
        return $donation->donate($items, $id);
    }

    public function getDonationHistory(): array {
        return $this->donationHistory;
    }

    public function setDonationHistory($donationHistory): void {
        $this->donationHistory = $donationHistory;
    }

    //create iterator

    public function createAppointment(string $location, string $date): bool {
        // Prepare the data for the new appointment
        $data = [
            'userId' => $this->id,
            'status' => 'pending', // Default status
            'date' => $date,
            'location' => $location,
            'employeeAssignedID' => null, // No employee assigned initially
        ];

        // Insert the appointment into the database
        try {
            $this->appointments [] = Appointment::storeObject($data);
            return true; // Success
        } catch (Exception $e) {
            // Log the error or handle it as needed
            error_log("Failed to create appointment: " . $e->getMessage());
            return false; // Failure
        }
    }

    /**
     * @return mixed
     */
    public function getAppointments()
    {
        return $this->appointments;
    }

    /**
     * @param mixed $appointments
     */
    public function setAppointments($appointments): void
    {
        $this->appointments = $appointments;
    }

    public function deleteAppointment($appointmentID): bool {

        try {
            echo gettype($appointmentID);
            Appointment::deleteObject($appointmentID);
            $this->appointments = array_filter($this->appointments, function ($appointment) use ($appointmentID) {
                return $appointment->getAppointmentID() !== $appointmentID;
            });

            return true;
        } catch (Exception $e) {

            error_log("Failed to delete appointment: " . $e->getMessage());
            return false;
        }
    }
    public function addToHistory(DonationDetails $donationDetails): void {
        $this->donationHistory[] = $donationDetails;
    }

    public function createIterator(): ICustomIterator {
        return new DonationHistoryIterator($this->donationHistory);
    }
}
?>
