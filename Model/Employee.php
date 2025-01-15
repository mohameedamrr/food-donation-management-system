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

class Employee extends UserEntity implements IObserver, IUpdateObject {
    private $role;
    private array $appointmentList = []; // array of Appointment objects
    private $department;
    private $salary;
    private $admin; // ISubject

    public function __construct($email) {
        $db =  new DatabaseManagerProxy('employee'); 
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
        }
        $sql2 = "SELECT * FROM employees WHERE id = $this->id";
        $row2 = $db->run_select_query($sql2)->fetch_assoc();
        if(isset($row2)) {
            $this->role = $row2["role"];
            $this->department = $row2["department"];
            $this->salary = $row2["salary"];
        }

        $sql3 = "SELECT * FROM appointments WHERE employeeAssignedID = $this->id";
        $rows = $db->run_select_query($sql3)->fetch_all(MYSQLI_ASSOC);
        foreach($rows as $row) {
            array_push($this->appointmentList, Appointment::readObject($row["appointmentID"]));
        }
    }

    ///// pass object
    public function changeAppointmentStatus(Appointment $appointment, string $status): void {
        for($i=0; $i <= count($this->appointmentList); $i++) {
            if ($this->appointmentList[$i]->getAppointmentID() == $appointment->getAppointmentID()) {
                $this->appointmentList[$i]->updateStatus($status);
                return;
            }
        }
    }

    // public function changeAppointmentStatus(Appointment $appointment, string $status): void {
    //     // Use array_search to find the appointment in the list
    //     $index = array_search($appointment, $this->appointmentList, true);
    
    //     // If the appointment is found, update its status
    //     if ($index !== false) {
    //         echo "foundddddddddddddddddddd";
    //         $this->appointmentList[$index]->updateStatus($status);
    //     }
    // }

    public function AppointmentDone(int $appointmentID){
        $this->admin->removeAppointment($appointmentID);
    }

    public function getAssignedAppointments(){
        return $this->appointmentList;
    }

    // public function update(): void {
    //     $appointmentTempList = $this->admin->getAppointmentsList();
    //     foreach ($appointmentTempList as $appointment) {
    //         if($appointment->getEmployeeAssignedID() == $this->getId()) {
    //             array_push($this->appointmentList,$appointment);
    //         }
    //     }
    // }

    public function update(): void {
        // Get the current list of appointments from the admin
        $appointmentTempList = $this->admin->getAppointmentsList();
    
        // Create a temporary array to store appointments that should be kept
        $updatedAppointmentList = [];
    
        // Loop through the admin's appointment list
        foreach ($appointmentTempList as $appointment) {
            // Check if the appointment is assigned to this employee
            if ($appointment->getEmployeeAssignedID() == $this->getId()) {
                // Avoid duplicates: Only add the appointment if it's not already in $this->appointmentList
                if (!in_array($appointment, $this->appointmentList, true)) {
                    $updatedAppointmentList[] = $appointment;
                }
            }
        }
    
        // Remove appointments from $this->appointmentList that are no longer in the admin's list
        foreach ($this->appointmentList as $existingAppointment) {
            // If the appointment is still in the admin's list and assigned to this employee, keep it
            if (in_array($existingAppointment, $appointmentTempList, true) && $existingAppointment->getEmployeeAssignedID() == $this->getId()) {
                $updatedAppointmentList[] = $existingAppointment;
            }
        }
    
        // Update $this->appointmentList with the new list
        $this->appointmentList = $updatedAppointmentList;
    }

    public function getDepartment() {
        return $this->department;
    }

    public function setDepartment($department) {
        $this->department = $department;
        $sql = "UPDATE employees SET `department` = '$this->department' WHERE `id` = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
        $sql = "UPDATE employees SET `role` = '$this->role' WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function getSalary() {
        return $this->salary;
    }

    public function setSalary($salary) {
        $this->salary = $salary;
        $sql = "UPDATE employees SET `salary` = '$this->salary' WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function setAdmin($admin) {
        if($admin != null) {
            $this->admin = $admin;
            $this->admin->addObserver($this);
        }
    }

    // public static function storeObject(array $data){
    //     $columns = implode(", ", array_map(fn($key) => "`$key`", array_keys($data)));
    //     $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
    //     $sql = "INSERT INTO `food_donation`.`Employees` ($columns) VALUES ($placeholders)";
    //     $db = DatabaseManager::getInstance();
    //     $db->runQuery($sql);
    //     return new Employee($data["email"]);
    // }

    // public static function storeObject(array $data) {
    //     $db = new DatabaseManagerProxy('admin'); /////////////////////////////
    
    //     // Extract user-specific data
    //     $userData = [
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'phone' => $data['phone'],
    //         'password' => $data['password'],
    //     ];
    
    //     // Insert into the users table
    //     $userColumns = implode(", ", array_map(fn($key) => "$key", array_keys($userData)));
    //     $userPlaceholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($userData)));
    //     $userSql = "INSERT INTO food_donation.users ($userColumns) VALUES ($userPlaceholders)";
    //     $db->runQuery($userSql);
    
    //     // Get the last inserted user ID
    //     $userId = $db->getLastInsertId();
    
    //     // Extract employee-specific data
    //     $employeeData = [
    //         'id' => $userId, // Use the user ID as the employee ID
    //         'role' => $data['role'],
    //         'department' => $data['department'],
    //         'salary' => $data['salary'],
    //     ];
    
    //     // Insert into the employees table
    //     $employeeColumns = implode(", ", array_map(fn($key) => "$key", array_keys($employeeData)));
    //     $employeePlaceholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($employeeData)));
    //     $employeeSql = "INSERT INTO food_donation.employees ($employeeColumns) VALUES ($employeePlaceholders)";
    //     $db->runQuery($employeeSql);
    
    //     // Return the newly created Employee object
    //     return new Employee($data["email"]);
    // }

    // public static function readObject($email){
    //     // $sql = "SELECT * FROM `food_donation`.`Employees` WHERE id = $id";
    //     // $db = DatabaseManager::getInstance();
    //     // $row = $db->run_select_query($sql)->fetch_assoc();
    //     // if(isset($row)) {
    //     //     return new Employee($row["id"], null);
    //     // }
    //     // return null;
    //     return new Employee($email, null);
    // }

    public function updateObject(array $data){
        $updates = [];
        foreach ($data as $prop => $value) {
            $this->{$prop} = $value;
            $value = is_numeric($value) ? $value : "'" . addslashes($value) . "'";
            $updates[] = "`$prop` = $value";
        }
        $sql = "UPDATE `food_donation`.`Employees` SET " . implode(", ", $updates) . " WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    // public static function deleteObject($id){
    //     $sql = "DELETE FROM `food_donation`.`Employees` WHERE id = $id";
    //     $db = DatabaseManager::getInstance();
    //     $db->runQuery($sql);
    // }
}
// $a1 = new Admin(1);
// echo $a1->getName();
// echo "<br>";
// echo $a1->getAppointmentsList()[0]->getStatus();
// echo "<br>";
// $newUser = $a1->createUser(array("name"=>"yarap", "email"=>"asaasdsaasasss@dssassdsaaass", "phone"=>"+23213", "password"=> "dfqwqdqdwq"));
// $newEmployee = $a1->createEmployee(array("id"=>$newUser->getId(), "role"=>"test", "department"=>"test"));
// $newEmployee->setAdmin($a1);
// echo $newEmployee->getName();
// $a1->deleteEmployee(3);
// $date = new DateTime();
// $newAppointment = Appointment::storeObject(array("status"=>"ongoing", "date"=>$date->format('Y-m-d'), "employeeAssignedID"=>"", "location"=>"cairoo"));
// echo "<br>";
// echo $newEmployee->getId();
// $a1->addAppointment($newAppointment);
// $a1->assignAppointment($newAppointment, $newEmployee->getId());
// echo "<br>";
// echo count($newEmployee->getAssignedAppointments());
// echo "<br>";
// echo $newEmployee->getAssignedAppointments()[0]->getStatus();
// $newEmployee->changeAppointmentStatus($newAppointment->getAppointmentID(), "yarapp");
// echo "<br>";
// echo $newEmployee->getAssignedAppointments()[0]->getStatus();
// echo "<br>";
// echo $newEmployee->getAssignedAppointments()[0]->getAppointmentLocation();
?>
