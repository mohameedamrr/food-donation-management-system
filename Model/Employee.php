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

    public function __construct($id, $admin) {
        $sql = "SELECT * FROM `food_donation`.`users` WHERE id = $id";
        $sql2 = "SELECT * FROM `food_donation`.`employees` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
        }
        $row2 = $db->run_select_query($sql2)->fetch_assoc();
        if(isset($row2)) {
            $this->role = $row2["role"];
            $this->department = $row2["department"];
        }

        $sql3 = "SELECT * FROM `food_donation`.`appointments` WHERE employeeAssignedID = $id";
        $rows = $db->run_select_query($sql3);
        foreach($rows as $row) {
            array_push($this->appointmentList, Appointment::readObject($row["appointmentID"]));
        }
        if($admin != null) {
            $this->admin = $admin;
            $this->admin->addObserver($this);
        }

    }

    public function changeAppointmentStatus(int $appointmentID, string $status): void {
        foreach ($this->appointmentList as $appointment) {
            if ($appointment->getAppointmentID() == $appointmentID) {
                $appointment->updateStatus($status);
                return;
            }
        }
    }

    public function AppointmentDone(int $appointmentID){
        $this->admin->removeTask($appointmentID);
    }

    public function getAssignedAppointments(){
        return $this->appointmentList;
    }

    public function update(): void {
        $this->appointmentList = $this->admin->getTasksList();
        foreach ($this->appointmentList as $appointment) {
            $appointment->assignEmployee($this->id);
        }
    }

    public function getDepartment() {
        return $this->department;
    }

    public function setDepartment($department) {
        $this->department = $department;
        $sql = "UPDATE `food_donation`.`employees` SET `department` = '$this->department' WHERE `id` = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
        $sql = "UPDATE `food_donation`.`employees` SET role = '$this->role' WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public static function storeObject(array $data){
        $columns = implode(", ", array_map(fn($key) => "`$key`", array_keys($data)));
        $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
        $sql = "INSERT INTO `food_donation`.`Employees` ($columns) VALUES ($placeholders)";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
        $lastInsertedId = $db->getLastInsertId();
        return new Employee($lastInsertedId, null);
    }

    public static function readObject($id){
        // $sql = "SELECT * FROM `food_donation`.`Employees` WHERE id = $id";
        // $db = DatabaseManager::getInstance();
        // $row = $db->run_select_query($sql)->fetch_assoc();
        // if(isset($row)) {
        //     return new Employee($row["id"], null);
        // }
        // return null;
        return new Employee($id, null);
    }

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

    public static function deleteObject($id){
        $sql = "DELETE FROM `food_donation`.`Employees` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
    }
}
$a1 = new Admin(1);
// echo $a1->getName();
// echo "<br>";
// echo $a1->getTasksList();
// echo "<br>";
// $newUser = $a1->createUser(array("name"=>"yarap", "email"=>"adsdasd@dadaa", "phone"=>"+23213", "password"=> "dfqwqdqdwq"));
// $newEmployee = $a1->createEmployee(array("id"=>$newUser->getId(), "role"=>"test", "department"=>"test"));
// echo $newEmployee->getName();
// $a1->deleteEmployee(3);
// $newAppointment = Appointment::storeObject(array("status"=>"ongoing", "date"=>(string) new DateTime('yyyy-mm-dd'), "employeeAssignedID"=>""));
// $a1->assignAppointment($newAppointment->getAppointmentID(), $newEmployee->getId());
// echo "<br>";
// echo $newEmployee->getAssignedAppointments();
// $newEmployee->changeAppointmentStatus($newAppointment->getAppointmentID(), "yarapp");
// echo "<br>";
// echo $newAppointment->getStatus();

?>
