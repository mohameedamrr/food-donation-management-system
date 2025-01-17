<?php
// spl_autoload_register(function ($class_name) {
//     $directories = [
//         '../Model/',
//         '../Controller/',
//         '../View/',
//         '../interfaces/',
//     ];
//     foreach ($directories as $directory) {
//         $file = __DIR__ . '/' . $directory . $class_name . '.php';
//         if (file_exists($file)) {
//             require_once $file;
//             return;
//         }
//     }
// });
// require_once 'ProxyDP/DatabaseManagerProxy.php';
class Admin extends UserEntity implements ISubject, IUpdateObject, IStoreObject, IDeleteObject {
    private array $appointmentsList = []; // array of tasks
    private array $observersList = []; // array of IObserver objects

    private array $commandsHistory = [];

    public function __construct($id) {
        $db = new DatabaseManagerProxy('admin');
        $sql = "SELECT * FROM `food_donation`.`users` WHERE id = $id";


        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"]);
        }
        $sql3 = "SELECT * FROM `food_donation`.`appointments`";
        $rows = $db->run_select_query($sql3)->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            array_push($this->appointmentsList, Appointment::readObject($row["appointmentID"]));
        }

    }

    /////////////////////
    public function assignAppointment(Appointment $appointment, $employeeID): void {
        for($i=0; $i <= count($this->appointmentsList); $i++) {
            if ($this->appointmentsList[$i]->getAppointmentID() == $appointment->getAppointmentID()) {
                $this->appointmentsList[$i]->assignEmployee($employeeID);
                break;
            }
        }
        $this->notifyObservers();
    }

    // public function assignAppointment(Appointment $appointment, $employeeID): void {
    //     $index = array_search($appointment, $this->appointmentsList, true);
    //     if ($index !== false) {
    //         $this->appointmentsList[$index]->assignEmployee($employeeID);
    //     }
    //     $this->notifyObservers();
    // }


    public function createEmployee(array $employeeData) {
        // return Employee::storeObject($employeeData);

        $db = new DatabaseManagerProxy('admin'); 

        $hashedPassword = md5($employeeData['password']);
        // Extract user-specific data
        $userData = [
            'name' => $employeeData['name'],
            'email' => $employeeData['email'],
            'phone' => $employeeData['phone'],
            'password' => $hashedPassword,
        ];
    
        // Insert into the users table
        $userColumns = implode(", ", array_map(fn($key) => "$key", array_keys($userData)));
        $userPlaceholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($userData)));
        $userSql = "INSERT INTO food_donation.users ($userColumns) VALUES ($userPlaceholders)";
        $db->runQuery($userSql);
    
        // Get the last inserted user ID
        $userId = $db->getLastInsertId();
    
        // Extract employee-specific data
        $employeeData = [
            'id' => $userId, // Use the user ID as the employee ID
            'role' => $employeeData['role'],
            'department' => $employeeData['department'],
            'salary' => $employeeData['salary'],
        ];
    
        // Insert into the employees table
        $employeeColumns = implode(", ", array_map(fn($key) => "$key", array_keys($employeeData)));
        $employeePlaceholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($employeeData)));
        $employeeSql = "INSERT INTO food_donation.employees ($employeeColumns) VALUES ($employeePlaceholders)";
        $db->runQuery($employeeSql);
    
        // Return the newly created Employee object
        return new Employee($userData["email"]);
    }

    // public function createUser(array $userData) {
    //     return BasicDonator::storeObject($userData);
    // }

    //***hnsebha kda */
    public function deleteEmployee(int $employeeID): void {
        // Employee::deleteObject($employeeID);
        $sql = "DELETE FROM `food_donation`.`Employees` WHERE id = $employeeID";
        $db =  new DatabaseManagerProxy('admin'); 
        $db->runQuery($sql);
    }

    ////////// add apoint
    public function addAppointment(Appointment $appointment): void {
        array_push($this->appointmentsList, $appointment);
    }

    //////
    // public function removeAppointment($appointmentID): void {
    //     foreach ($this->appointmentsList as $appointment) {
    //         if ($appointment->getAppointmentID() === $appointmentID) {
    //             $this->appointmentsList = array_diff($this->appointmentsList, [$appointment]);
    //             $this->appointmentsList = array_values($this->appointmentsList);
    //             break;
    //         }
    //     }
    //     $this->notifyObservers();
    // }

    // public function removeAppointment($appointmentID): void {
    //     foreach ($this->appointmentsList as $key => $appointment) {
    //         if ($appointment->getAppointmentID() === $appointmentID) {
    //             // Remove the appointment from the list using its key
    //             unset($this->appointmentsList[$key]);
    //             // Reindex the array to avoid gaps in the keys
    //             $this->appointmentsList = array_values($this->appointmentsList);
    //             break;
    //         }
    //     }
    //     $this->notifyObservers();
    // }

    public function removeAppointment($appointmentID): void {
        foreach ($this->appointmentsList as $key => $appointment) {
            if ($appointment->getAppointmentID() == $appointmentID) {
                // Remove the appointment from the list using its key
                unset($this->appointmentsList[$key]);
                // Reindex the array to avoid gaps in the keys
                $this->appointmentsList = array_values($this->appointmentsList);
    
                // Delete the appointment from the database
                Appointment::deleteObject($appointmentID);
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

    public function getAppointmentsList(){
        return $this->appointmentsList;
    }

    //get all employees
    public function getAllEmployees(){
        $employeeObjects = [];
        $adminProxy = new DatabaseManagerProxy('admin');
        $employeesData  = $adminProxy->run_select_query("SELECT * FROM employees")->fetch_all(MYSQLI_ASSOC);
        foreach ($employeesData as $employeeData) {
            // Fetch the user's email from the users table using the employee's ID
            $userId = $employeeData['id'];
            $userData = $adminProxy->run_select_query("SELECT email FROM users WHERE id = $userId")->fetch_assoc();
            if ($userData) {
                // Create an Employee object using the email
                $employee = new Employee($userData['email']);
                $employeeObjects[] = $employee; // Add the Employee object to the list
            }
        }
        return $employeeObjects;
    }

    public static function storeObject(array $data) {
        $hashedPassword = md5($data['password']);
        $data['password'] = $hashedPassword;
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
            return new self($id);
        } catch (Exception $e) {

            throw $e;
        }
    }

    public function updateObject(array $data) {
        $proxy = new DatabaseManagerProxy('admin');

        foreach ($data as $prop => $value) {
            $this->{$prop} = $value;
        }
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

    public function addToCommandsHistory($command) {
        $this->commandsHistory[] = $command;
    }
    public function getCommandsHistory()
    {
        return $this->commandsHistory;
    }
    public function setCommandsHistory($commandsHistory)
    {
        $this->commandsHistory = $commandsHistory;

    }
    public function executeCommand(): void {
        $this->commandsHistory[count($this->commandsHistory) - 1]->execute();
    }
    public function undoCommand(): void {
        if (count($this->commandsHistory) != 0) {
            $this->commandsHistory[count($this->commandsHistory) - 1]->undo();
            array_pop($this->commandsHistory);
        }
    }

    public function setAppointmentsList($appointmentsList) {
        $this->appointmentsList = $appointmentsList;
        $this->notifyObservers();
    }
}
?>