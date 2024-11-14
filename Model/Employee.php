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
        echo $id;
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
        /////////////////////////$this->appointmentList = null;
        $this->admin = $admin;
        $this->admin->addObserver($this);
    }

    public function changeAppointmentStatus(int $appointmentID, string $status): void {
        foreach ($this->appointmentList as $appointment) {
            if ($appointment->getId() == $appointmentID) {
                $appointment->setStatus($status);
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
        return new Employee($lastInsertedId);
    }

    public static function readObject($id){
        $sql = "SELECT * FROM `food_donation`.`Employees` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            return new Employee($row["id"]);
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
        $sql = "UPDATE `food_donation`.`Employees` SET " . implode(", ", $updates) . " WHERE id = $this->id";
        DatabaseManager::getInstance()->runQuery($sql);
    }

    public static function deleteObject($id){
        $sql = "DELETE FROM `food_donation`.`Employees` WHERE id = $id";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
    }
}
?>
