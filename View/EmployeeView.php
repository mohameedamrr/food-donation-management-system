<?php
class EmployeeView {
    // Display employee details
    public function showEmployeeDetails($employeeDetails) {
        echo '<h2>Employee Details</h2>';
        echo '<p>Name: ' . $employeeDetails['name'] . '</p>';
        echo '<p>Role: ' . $employeeDetails['role'] . '</p>';
        echo '<p>Department: ' . $employeeDetails['department'] . '</p>';
        echo '<p>Email: ' . $employeeDetails['email'] . '</p>';
    }

    // Display form to add a new employee
    public function showAddEmployeeForm() {
        echo '
        <form method="post" action="addEmployee.php">
            <input type="text" name="name" placeholder="Employee Name" required>
            <input type="text" name="role" placeholder="Role" required>
            <input type="text" name="department" placeholder="Department" required>
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Add Employee</button>
        </form>';
    }

    // Display all employees
    public function showAllEmployees($employees) {
        echo '<h2>All Employees</h2>';
        foreach ($employees as $employee) {
            echo '<div>';
            echo '<p>' . $employee['name'] . '</p>';
            echo '<a href="viewEmployee.php?id=' . $employee['id'] . '">View Details</a>';
            echo '</div>';
        }
    }

    // Display success message for employee update
    public function showEmployeeUpdateSuccess() {
        echo '<p>Employee updated successfully!</p>';
    }
}
?>
