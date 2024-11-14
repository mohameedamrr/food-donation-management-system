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

class Admin extends UserEntity implements ISubject, ICRUD {
    private $tasksList; // array of tasks
    private $observersList; // array of IObserver objects

    public function __construct() {
        $sql3 = "SELECT * FROM `food_donation`.`appointments`";
        $db = DatabaseManager::getInstance();
        $rows = $db->run_select_query($sql3);
        foreach($rows as $row) {
            array_push($this->tasksList, Appointment::readObject($row["appointmentID"]));
        }

    }

    public function assignAppointment($appointmentID, $employeeID): void {
        foreach ($this->tasksList as $appointment) {
            if ($appointment->getAppointmentID() == $appointmentID) {
                $appointment->assignEmployee($employeeID);
                return;
            }
        }
        $this->notifyObservers();
    }

    public function createEmployee(array $employeeData) {
        return Employee::storeObject($employeeData);
    }

    public function deleteEmployee(int $employeeID): void {
        Employee::deleteObject($employeeID);
    }

    public function addTask($taskID): void {
        array_push($this->tasksList, Appointment::readObject($taskID));
        $this->notifyObservers();
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

    public static function storeObject(array $data){
        $columns = implode(", ", array_map(fn($key) => "`$key`", array_keys($data)));
        $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
        $sql = "INSERT INTO `food_donation`.`Admins` ($columns) VALUES ($placeholders)";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
        $lastInsertedId = $db->getLastInsertId();
        return new Admin($lastInsertedId); ///////////////////
    }
    public static function readObject($id){
        $sql = "SELECT * FROM `food_donation`.`Admins` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            return new Admin($row["id"]); //////////////////////
        }
        return null;
    }
    public function updateObject(array $data){
        $updates = [];
        foreach ($data as $prop => $value) { 
            $this->{$prop} = $value;
            $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $updates[] = "`$prop` = $value";
        }
        $sql = "UPDATE `food_donation`.`Admins` SET " . implode(", ", $updates) . " WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }
    public static function deleteObject($id){
        $sql = "DELETE FROM `food_donation`.`Admins` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
    }

    public function getTasksList(){
        return $this->tasksList;
    }

}
?>

