<?php
require_once __DIR__ . '/../interfaces/Subject.php';
require_once 'Employee.php';
require_once 'Location.php';

class Appointment implements Subject {
    private $status;
    private $time;
    private $date;
    private $appointmentID;
    private $observers;
    private $employeeAssigned;
    private $location;

    public function __construct($appointmentID, $date, $time, Location $location) {
        $this->appointmentID = $appointmentID;
        $this->date = $date;
        $this->time = $time;
        $this->location = $location;
        $this->status = 'Scheduled';
        $this->observers = array();
        $this->employeeAssigned = null;
    }

    public function updateStatus($status) {
        $this->status = $status;
        $this->notify();
    }

    public function assignEmployee(Employee $employee) {
        $this->employeeAssigned = $employee;
        $employee->addAppointment($this);
        $this->notify();
    }

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function getAppointmentID() {
        return $this->appointmentID;
    }
    public function getStatus() {
		return $this->status;
	}

	public function setStatus($value) {
		$this->status = $value;
	}

	public function getTime() {
		return $this->time;
	}

	public function setTime($value) {
		$this->time = $value;
	}

	public function getDate() {
		return $this->date;
	}

	public function setDate($value) {
		$this->date = $value;
	}

	public function setAppointmentID($value) {
		$this->appointmentID = $value;
	}

	public function getObservers() {
		return $this->observers;
	}

	public function setObservers($value) {
		$this->observers = $value;
	}

	public function getEmployeeAssigned() {
		return $this->employeeAssigned;
	}

	public function setEmployeeAssigned($value) {
		$this->employeeAssigned = $value;
	}

	public function getLocation() {
		return $this->location;
	}

	public function setLocation($value) {
		$this->location = $value;
	}
}
?>


