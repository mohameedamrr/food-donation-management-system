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
require_once 'ProxyDP/DatabaseManagerProxy.php';
class Admin extends UserEntity implements ISubject, IUpdateObject, IStoreObject, IDeleteObject {
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

    /////////////////////
    public function assignAppointment($appointmentID, $employeeID): void {
        for($i=0; $i <= count($this->tasksList); $i++) {
            if ($this->tasksList[$i]->getAppointmentID() == $appointmentID) {
                $this->tasksList[$i]->assignEmployee($employeeID);
                break;
            }
        }
        $this->notifyObservers();
    }

    //edit donation item cost


    public function createEmployee(array $employeeData) {
        return Employee::storeObject($employeeData);
    }

    public function createUser(array $userData) {
        return BasicDonator::storeObject($userData);
    }

    //***hnsebha kda */
    public function deleteEmployee(int $employeeID): void {
        Employee::deleteObject($employeeID);
    }

    ////////// add apoint
    public function addTask($taskID): void {
        array_push($this->tasksList, Appointment::readObject($taskID));
    }

    //////
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
                unset($this->observersList[$key]);
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

    //get all employees

    public static function storeObject(array $data) {
        $proxy = new DatabaseManagerProxy('admin');
        if (empty($data['name']) || empty($data['email']) || empty($data['phone']) || empty($data['password'])) {
            throw new Exception("All fields (name, email, phone, password) are required.");
        }
        try {
            // Insert into `users` table
            $queryUsers = "INSERT INTO users (name, email, phone, password) VALUES 
                      ('{$data['name']}', '{$data['email']}', '{$data['phone']}', '{$data['password']}')";
            if (!$proxy->runQuery($queryUsers)) {
                throw new Exception("Failed to store admin object in users table.");
            }
            // Get the last inserted ID
            $id = $proxy->getLastInsertId();
            // Insert into `employees` table
            $queryEmployees = "INSERT INTO employees (id, role, department) VALUES 
                          ($id, 'Manager', 'Administration')";
            if (!$proxy->runQuery($queryEmployees)) {
                throw new Exception("Failed to store admin object in employees table.");
            }
            return new self($id, $data['name'], $data['email'], $data['phone'], $data['password']);
        } catch (Exception $e) {

            throw $e;
        }
    }

    public static function updateObject(array $data) {
        $proxy = new DatabaseManagerProxy('admin');


        try {
            // Update `users` table
            $queryUsers = "UPDATE users SET 
                       name = '{$data['name']}', 
                       email = '{$data['email']}', 
                       phone = '{$data['phone']}' 
                       WHERE id = {$data['id']}";
            if (!$proxy->runQuery($queryUsers)) {
                throw new Exception("Failed to update admin object in users table.");
            }

            // Update `employees` table
            $queryEmployees = "UPDATE employees SET 
                           role = 'Manager', 
                           department = 'Administration' 
                           WHERE id = {$data['id']}";
            if (!$proxy->runQuery($queryEmployees)) {
                throw new Exception("Failed to update admin object in employees table.");
            }


            return true;
        } catch (Exception $e) {

            throw $e;
        }
    }

    public static function deleteObject($id) {
        $proxy = new DatabaseManagerProxy('admin');


        try {
            // Delete from `employees` table
            $queryEmployees = "DELETE FROM employees WHERE id = $id";
            if (!$proxy->runQuery($queryEmployees)) {
                throw new Exception("Failed to delete admin object in employees table.");
            }

            // Delete from `users` table
            $queryUsers = "DELETE FROM users WHERE id = $id";
            if (!$proxy->runQuery($queryUsers)) {
                throw new Exception("Failed to delete admin object in users table.");
            }


            return true;
        } catch (Exception $e) {

            throw $e;
        }
    }


    public function getId() {
        return $this->id;
    }



}
?>

