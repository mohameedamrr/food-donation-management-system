<?php
class ChangeAppointmentStatusCommand implements ICommand {
    private $employee;
    private $appointment;
    private $newStatus;
    private $previousStatus;

    public function __construct(Appointment $appointment,string $newStatus, string $previousStatus) {
        $this->appointment = $appointment;
        $this->newStatus = $newStatus;
        $this->previousStatus = $previousStatus;
        $donorProxy = new DatabaseManagerProxy('admin');
        $row = $donorProxy->run_select_query("SELECT * FROM employees WHERE 'role' = 'Manager' ")->fetch_assoc();
        $user_id = $row['id'];
        $row2 = $donorProxy->run_select_query("SELECT * FROM users WHERE id = $user_id ")->fetch_assoc();
        $this->employee = new Employee($row2['email']);
    }
    public function execute(): void{
        $this->employee->changeAppointmentStatus($this->appointment, $this->newStatus);
    }
    public function undo(): void{
        $this->employee->changeAppointmentStatus($this->appointment, $this->previousStatus);
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setEmployee($employee)
    {
        $this->employee = $employee;
    }

    public function getAppointment()
    {
        return $this->appointment;
    }

    public function setAppointment($appointment)
    {
        $this->appointment = $appointment;
    }

    public function getNewStatus()
    {
        return $this->newStatus;
    }

    public function setNewStatus($newStatus)
    {
        $this->newStatus = $newStatus;

    }

    public function getPreviousStatus()
    {
        return $this->previousStatus;
    }

    public function setPreviousStatus($previousStatus)
    {
        $this->previousStatus = $previousStatus;

    }
}
?>