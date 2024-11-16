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

class Employee extends UserEntity implements IObserver, ICRUD {
    private $role;
    private array $appointmentList = []; // array of Appointment objects
    private $department;
    private $admin; // ISubject

    public function __construct($email) {
        $sql = "SELECT * FROM `food_donation`.`users` WHERE email = '$email'";
        $db = DatabaseManager::getInstance();
        $row = $db->run_select_query($sql)->fetch_assoc();
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"], new NormalMethod());
        }
        $sql2 = "SELECT * FROM `food_donation`.`employees` WHERE id = $this->id";
        $row2 = $db->run_select_query($sql2)->fetch_assoc();
        if(isset($row2)) {
            $this->role = $row2["role"];
            $this->department = $row2["department"];
        }

        $sql3 = "SELECT * FROM `food_donation`.`appointments` WHERE employeeAssignedID = $this->id";
        $rows = $db->run_select_query($sql3)->fetch_all(MYSQLI_ASSOC);
        foreach($rows as $row) {
            array_push($this->appointmentList, Appointment::readObject($row["appointmentID"]));
        }
    }

    public function changeAppointmentStatus(int $appointmentID, string $status): void {
        for($i=0; $i <= count($this->appointmentList); $i++) {
            if ($this->appointmentList[$i]->getAppointmentID() == $appointmentID) {
                $this->appointmentList[$i]->updateStatus($status);
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
        $appointmentTempList = $this->admin->getTasksList();
        foreach ($appointmentTempList as $appointment) {
            if($appointment->getEmployeeAssignedID() == $this->getId()) {
                array_push($this->appointmentList,$appointment);
            }
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

    public function setAdmin($admin) {
        if($admin != null) {
            $this->admin = $admin;
            $this->admin->addObserver($this);
        }
    }

    public static function storeObject(array $data){
        $columns = implode(", ", array_map(fn($key) => "`$key`", array_keys($data)));
        $placeholders = implode(", ", array_map(fn($value) => is_numeric($value) ? $value : "'" . addslashes($value) . "'", array_values($data)));
        $sql = "INSERT INTO `food_donation`.`Employees` ($columns) VALUES ($placeholders)";
        $db = DatabaseManager::getInstance();
        $db->runQuery($sql);
        return new Employee($data["email"]);
    }

    public static function readObject($email){
        // $sql = "SELECT * FROM `food_donation`.`Employees` WHERE id = $id";
        // $db = DatabaseManager::getInstance();
        // $row = $db->run_select_query($sql)->fetch_assoc();
        // if(isset($row)) {
        //     return new Employee($row["id"], null);
        // }
        // return null;
        return new Employee($email, null);
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
echo $a1->getName();
echo "<br>";
echo $a1->getTasksList()[0]->getStatus();
echo "<br>";
$newUser = $a1->createUser(array("name"=>"yarap", "email"=>"asaasdsaasasss@dssassdsaaass", "phone"=>"+23213", "password"=> "dfqwqdqdwq"));
$newEmployee = $a1->createEmployee(array("id"=>$newUser->getId(), "role"=>"test", "department"=>"test", "email" => $newUser->getEmail()));
$newEmployee->setAdmin($a1);
echo $newEmployee->getName();
$a1->deleteEmployee(3);
$date = new DateTime();
$newAppointment = Appointment::storeObject(array("status"=>"ongoing", "date"=>$date->format('Y-m-d'), "employeeAssignedID"=>"", "location"=>"cairoo"));
echo "<br>";
echo $newEmployee->getId();
$a1->addTask($newAppointment->getAppointmentID());
$a1->assignAppointment($newAppointment->getAppointmentID(), $newEmployee->getId());
echo "<br>";
echo count($newEmployee->getAssignedAppointments());
echo "<br>";
echo $newEmployee->getAssignedAppointments()[0]->getStatus();
$newEmployee->changeAppointmentStatus($newAppointment->getAppointmentID(), "yarapp");
echo "<br>";
echo $newEmployee->getAssignedAppointments()[0]->getStatus();
echo "<br>";
echo $newEmployee->getAssignedAppointments()[0]->getAppointmentLocation();
?>
