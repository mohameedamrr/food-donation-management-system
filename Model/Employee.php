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

class Employee extends UserEntity implements IObserver {
    private $role;
    private $appointmentList; // array of Appointment objects
    private $department;
    private $admin; // ISubject

    public function __construct($id) {
        $sql = "SELECT * FROM `food_donation`.`employees` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
        }
        
        foreach ($row as $prop => $value) {
            if (property_exists($this, $prop)) {
                $this->{$prop} = $value;
            }
        }
        
    }

    public function changeAppointmentStatus(int $appointmentID, string $status): void {
        // Change the status of an appointment
    }

    public function getAssignedAppointments(): array {
        return $this->appointmentList;
    }

    public function update(ISubject $subject): void {
        // Update method as per IObserver interface
    }
}
?>
