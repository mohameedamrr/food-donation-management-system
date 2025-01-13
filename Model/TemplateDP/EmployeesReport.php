<?php
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

    protected function generateBody() {
        $body = "Employee Details:\n";
        foreach ($this->employees as $employee) {
            $body .= "- Name: " . $employee['name'] . ", Role: " . $employee['role'] . ", Salary: $" . $employee['salary'] . ", Performance: " . $employee['performance'] . "\n";
        }
        $body .= "-------------------\n";
        $body .= "Total Employees: " . $this->calculateTotalRecords() . "\n";
        $body .= "Highest Salary: $" . $this->getHighestSalary() . "\n";
        $body .= "Average Salary: $" . number_format($this->calculateAverageSalary(), 2) . "\n";
        $body .= "Top Performer: " . $this->getTopPerformer() . "\n";
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
        $maxPerformance = max($performances);
        $index = array_search($maxPerformance, $performances);
        return $this->employees[$index]['name'];
    }
}
?>