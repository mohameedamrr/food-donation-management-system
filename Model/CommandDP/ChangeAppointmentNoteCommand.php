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
        $row = $donorProxy->run_select_query("SELECT * FROM employees WHERE 'role' = 'Manager' ")->fetch_assoc();
        $user_id = $row['id'];
        $row2 = $donorProxy->run_select_query("SELECT * FROM users WHERE id = $user_id ")->fetch_assoc();
        $this->employee = new Employee($row2['email']);
    }
    public function execute(): void{
        $this->employee->changeAppointmentNote($this->appointment, $this->newNote);
    }
    public function undo(): void{
        $this->employee->changeAppointmentNote($this->appointment, $this->previousNote);
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