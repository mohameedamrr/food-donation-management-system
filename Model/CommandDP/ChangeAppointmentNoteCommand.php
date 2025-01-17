<?php
class ChangeAppointmentNoteCommand implements ICommand {
    private $appointment;
    private $newNote;
    private $previousNote;
    private $employee;

    public function __construct(Appointment $appointment,string $newNote, string $previousNote) {
        $this->appointment = $appointment;
        $this->newNote = $newNote;
        $this->previousNote = $previousNote;
        $donorProxy = new DatabaseManagerProxy('admin');
        $employeeID = $appointment->getEmployeeAssignedID();
        error_log("vcvvdsvsdvsdvsdvd");
        error_log($employeeID.'');
        $row = $donorProxy->run_select_query("SELECT * FROM users WHERE id = $employeeID")->fetch_assoc();
        if(isset($row)) {
            $this->employee = new Employee($row['email']);
        }
    }
    public function execute(): void{
        $this->employee->addNoteToAppointment($this->appointment, $this->newNote);
    }
    public function undo(): void{
        $this->employee->addNoteToAppointment($this->appointment, $this->previousNote);
    }
 
    public function getAppointment()
    {
        return $this->appointment;
    }
 
    public function setAppointment($appointment)
    {
        $this->appointment = $appointment;

    }
    public function getNewNote()
    {
        return $this->newNote;
    }

    public function setNewNote($newNote)
    {
        $this->newNote = $newNote;

    }

    public function getPreviousNote()
    {
        return $this->previousNote;
    }

    public function setPreviousNote($previousNote)
    {
        $this->previousNote = $previousNote;

    }

    public function getEmployee()
    {
        return $this->employee;
    } 
    public function setEmployee($employee)
    {
        $this->employee = $employee;

    }
}
?>