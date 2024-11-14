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
    private $role;
    private $tasksList; // array of tasks
    private $observersList; // array of IObserver objects

    public function __construct()
    {
        
    }

    public function assignAppointment(int $appointmentID, Employee $employee): void {
        // Assign an appointment to an employee
        //create appointment using CRUD and add employee to its data
    }

    public function createEmployee(array $employeeData) {
        // Create a new employee and return it
        return Employee::storeObject($employeeData);
    }

    public function deleteEmployee(int $employeeID): void {
        // Delete an employee
        Employee::deleteObject($employeeID);
    }

    public function addTask(string $task): void {
        // Add a new task
        array_push($this->tasksList, $task);
        $this->notifyObservers();
    }

    public function removeTask(string $taskID): void {
        // Loop through the tasksList
        foreach ($this->tasksList as $key => $existingTask) {
            // Check if the existing task's ID matches the provided taskID
            if ($existingTask->getId() === $taskID) {
                // Remove the task from the list
                unset($this->tasksList[$key]);
                // Reindex the array to avoid gaps in the keys
                $this->tasksList = array_values($this->tasksList);
                break;
            }
        }
        
        // If the task was not found, you can notify observers or handle accordingly
        $this->notifyObservers();  // Notify if the task wasn't found (optional)
    }
    

    public function addObserver(IObserver $observer): void {
        // Add an observer
        array_push($this->observersList, $observer);
        
    }

    public function removeObserver(IObserver $observer): void {
        // Remove an observer
        foreach ($this->observersList as $key => $existingObserver) {
            // Check if the observer is the same as the one to be removed
            if ($existingObserver === $observer) {
                // Remove the observer from the list
                unset($this->observerList[$key]);
                // Reindex the array to avoid gaps in the keys
                $this->observersList = array_values($this->observersList);
                return;
            }
        }
    }

    public function notifyObservers(): void {
        // Notify all observers
        foreach ($this->observersList as $observer) {
            // Call the update method on each observer
            $observer->update(); // Assuming $this is the ISubject that the observer is observing
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

