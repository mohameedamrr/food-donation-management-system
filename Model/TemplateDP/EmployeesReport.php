<?php
require_once '..\ProxyDP\DatabaseManagerProxy.php';

class EmployeesReport extends ReportTemplate {
    private $employees;

    public function __construct() {
        $this->employees = [
            ["name" => "John Doe", "role" => "Manager", "salary" => 5000, "performance" => "Excellent"],
            ["name" => "Jane Smith", "role" => "Developer", "salary" => 4000, "performance" => "Good"],
            ["name" => "Alice Johnson", "role" => "Designer", "salary" => 3500, "performance" => "Average"],
        ];
    }

    protected function getReportType() {
        return "Employees Report";
    }

    protected function calculateTotalRecords() {
        return count($this->employees);
    }

    protected function prepareReportData(){
        $adminProxy = new DatabaseManagerProxy('admin');
        $this->employees = $adminProxy->run_select_query("SELECT * FROM employees")->fetch_all(MYSQLI_ASSOC);
        for($i = 0; $i< count($this->employees); $i++){
            $this->employees[$i]['name'] = $adminProxy->run_select_query("SELECT * FROM users where id = {$this->employees[$i]['id']}")->fetch_all(MYSQLI_ASSOC)[0]['name'];
        }
    }

    protected function generateBody() {
        $body = "<h2>Employee Details:</h2>";
        foreach ($this->employees as $employee) {
            $body .= "<p><strong>Name:</strong> " . $employee['name'] . ", <strong>Role:</strong> " . $employee['role'] . ", <strong>Salary:</strong> $" . $employee['salary'] . "</p>";
        }
        $body .= "<hr>";
        $body .= "<p><strong>Total Employees:</strong> " . $this->calculateTotalRecords() . "</p>";
        $highestSalaryDetails = $this->getHighestSalary();
        $body .= "<p><strong>Highest Salary:</strong> $" . $highestSalaryDetails['salary'] . " (Employee: " . $highestSalaryDetails['name'] . ", Role: " . $highestSalaryDetails['role'] . ")</p>";        
        $body .= "<p><strong>Average Salary:</strong> $" . number_format($this->calculateAverageSalary(), 2) . "</p>";
        return $body;
    }

    private function getHighestSalary() {
        $highestSalary = 0;
        $highestSalaryEmployee = [];

        foreach ($this->employees as $employee) {
            if ($employee['salary'] > $highestSalary) {
                $highestSalary = $employee['salary'];
                $highestSalaryEmployee = [
                    'name' => $employee['name'],
                    'role' => $employee['role'],
                    'salary' => $employee['salary']
                ];
            }
        }

        return $highestSalaryEmployee;
    }

    private function calculateAverageSalary() {
        $totalSalary = array_sum(array_column($this->employees, 'salary'));
        return $totalSalary / count($this->employees);
    }
}
?>