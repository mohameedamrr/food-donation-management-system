<?php
//require __DIR__ . '/../ProxyDP/DatabaseManagerProxy.php'; 
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
        // foreach ($this->employees as $employee){
        //     $employee['name'] = $adminProxy->run_select_query("SELECT * FROM users where id = {$employee['id']}")->fetch_all(MYSQLI_ASSOC)[0]['name'];
        //     $this->employees->array_push($employee);
        //     echo $employee['name'] ;
        // } 
    }

    protected function generateBody() {
        $body = "Employee Details:\n";
        foreach ($this->employees as $employee) {
            $body .= "- Name: " . $employee['name'] . ", Role: " . $employee['role'] . ", Salary: $" . "20000" . ", Performance: " . 'Excellent' . "\n";
        }
        $body .= "-------------------\n";
        $body .= "Total Employees: " . $this->calculateTotalRecords() . "\n";
        //$body .= "Highest Salary: $" . $this->getHighestSalary() . "\n";
        //$body .= "Average Salary: $" . number_format($this->calculateAverageSalary(), 2) . "\n";
        //$body .= "Top Performer: " . $this->getTopPerformer() . "\n";
        return $body;
    }

    private function getHighestSalary() {
        return max(array_column($this->employees, 'salary'));
    }

    private function calculateAverageSalary() {
        $totalSalary = array_sum(array_column($this->employees, 'salary'));
        return $totalSalary / count($this->employees);
    }

    private function getTopPerformer() {
        $performances = array_column($this->employees, 'performance');
        //$maxPerformance = max($performances);
        //$index = array_search($maxPerformance, $performances);
        //return $this->employees[$index]['name'];
    }
}
?>