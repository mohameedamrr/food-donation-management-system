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

class Employee extends UserEntity implements IObserver, IUpdateObject {
    private $role;
    private array $appointmentList = [];
    private $department;
    private $salary;
    private $admin;

    public function __construct($email) {
        $db =  new DatabaseManagerProxy('employee');
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $row = $db->run_select_query($sql)->fetch_assoc();
        $this->email = $email;
        if($row == null) return;
        if(isset($row)) {
            parent::__construct($row["id"], $row["name"], $row["email"], $row["phone"], $row["password"]);
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

    public function changeAppointmentStatus(Appointment $appointment, string $status): void {
        for($i=0; $i <= count($this->appointmentList); $i++) {
            if ($this->appointmentList[$i]->getAppointmentID() == $appointment->getAppointmentID()) {
                $this->appointmentList[$i]->updateStatus($status);
                return;
            }
        }
    }

    public function AppointmentDone(int $appointmentID){
        $this->admin->removeAppointment($appointmentID);
    }

    public function getAssignedAppointments(){
        return $this->appointmentList;
    }

    public function update(): void {
        $appointmentTempList = $this->admin->getAppointmentsList();

        $updatedAppointmentList = [];
        foreach ($appointmentTempList as $appointment) {
            if ($appointment->getEmployeeAssignedID() == $this->getId()) {
                if (!in_array($appointment, $this->appointmentList, true)) {
                    $updatedAppointmentList[] = $appointment;
                }
            }
        }

        foreach ($this->appointmentList as $existingAppointment) {
            if (in_array($existingAppointment, $appointmentTempList, true) && $existingAppointment->getEmployeeAssignedID() == $this->getId()) {
                $updatedAppointmentList[] = $existingAppointment;
            }
        }

        // for ($i = 0; $i < count($updatedAppointmentList); $i++) {
        //     $existingAppointment = $updatedAppointmentList[$i];
        //     for ($j = 0; $j < count($appointmentTempList); $j++) {
        //         $appointment = $appointmentTempList[$j];
        //         if ($existingAppointment->getAppointmentID() == $appointment->getAppointmentID()) {
        //             if ($existingAppointment->getNote() != $appointment->getNote()) {
        //                 $existingAppointment->setNote($appointment->getNote());
        //             } else if ($existingAppointment->getStatus() != $appointment->getStatus()) {
        //                 $existingAppointment->updateStatus($appointment->getStatus());
        //             }
        //         }
        //     }
        // }
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

    public function getAppointmentByStatus($status): array {
        $newList = [];
        for($i=0; $i < count($this->appointmentList); $i++) {
            if($this->appointmentList[$i]->getStatus() == $status) {
                $newList[] = $this->appointmentList[$i];
            }
        }
        return $newList;
    }

    public function getUpcomingAppointments(): array {
        $today = new DateTime('today');
        $newList = [];
        foreach ($this->appointmentList as $appointment) {
            $appointmentDate = $appointment->getDate();
            if (is_string($appointmentDate)) {
                $appointmentDate = new DateTime($appointmentDate);
            }
            if ($appointmentDate > $today) {
                $newList[] = $appointment;
            }
        }
        return $newList;
    }

    public function getPastAppointments(): array {
        $today = new DateTime('today');
        $newList = [];
        foreach ($this->appointmentList as $appointment) {
            $appointmentDate = $appointment->getDate();
            if (is_string($appointmentDate)) {
                $appointmentDate = new DateTime($appointmentDate);
            }
            if ($appointmentDate < $today) {
                $newList[] = $appointment;
            }
        }
        return $newList;
    }

    public function addNoteToAppointment(Appointment $appointment, String $note): void {
        $appointment->setNote($note);
        error_log(count($this->appointmentList));
        for($i=0; $i <= count($this->appointmentList); $i++) {
            error_log($this->appointmentList[$i]->getAppointmentID());
            if ($this->appointmentList[$i]->getAppointmentID() == $appointment->getAppointmentID()) {
                $this->appointmentList[$i]->setNote($note);
                return;
            }
        }
        $appointment->updateObject(['note' => $note]);
    }

    public function changeDonationDescription(DonationDetails $donationDetails, String $description): void {
        $donationDetails->setDescription($description);
        $donationDetails->updateObject(['description' => $description]);
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
}

?>