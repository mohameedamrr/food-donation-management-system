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

class Admin extends UserEntity implements ISubject {
    private array $tasksList = []; // array of tasks
    private array $observersList = []; // array of IObserver objects

    public function __construct($id) {
        $db = DatabaseManager::getInstance();
        $sql = "SELECT * FROM `food_donation`.`users` WHERE id = $id";


        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
        }
        $sql3 = "SELECT * FROM `food_donation`.`appointments`";
        $rows = $db->run_select_query($sql3)->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            array_push($this->tasksList, Appointment::readObject($row["appointmentID"]));
        }

    }

    public function assignAppointment($appointmentID, $employeeID): void {
        for($i=0; $i <= count($this->tasksList); $i++) {
            if ($this->tasksList[$i]->getAppointmentID() == $appointmentID) {
                $this->tasksList[$i]->assignEmployee($employeeID);
                break;
            }
        }
        $this->notifyObservers();
    }

    public function createEmployee(array $employeeData) {
        return Employee::storeObject($employeeData);
    }

    public function createUser(array $userData) {
        return BasicDonator::storeObject($userData);
    }

    public function deleteEmployee(int $employeeID): void {
        Employee::deleteObject($employeeID);
    }

    public function addTask($taskID): void {
        array_push($this->tasksList, Appointment::readObject($taskID));
    }

    public function removeTask($taskID): void {
        foreach ($this->tasksList as $appointment) {
            if ($appointment->getId() === $taskID) {
                $this->tasksList = array_diff($this->tasksList, [$appointment]);
                $this->tasksList = array_values($this->tasksList);
                break;
            }
        }
        $this->notifyObservers();
    }

    public function addObserver(IObserver $observer): void {
        array_push($this->observersList, $observer);
    }

    public function removeObserver(IObserver $observer): void {
        foreach ($this->observersList as $key => $existingObserver) {
            if ($existingObserver === $observer) {
                unset($this->observerList[$key]);
                $this->observersList = array_values($this->observersList);
                return;
            }
        }
    }

    public function notifyObservers(): void {
        foreach ($this->observersList as $observer) {
            $observer->update();
        }
    }

    public function getTasksList(){
        return $this->tasksList;
    }

}
?>

