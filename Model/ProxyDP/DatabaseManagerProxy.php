<?php
//require '../../interfaces/IDatabaseManager.php';
require_once '../../interfaces/IDatabaseManager.php';
require_once '../DatabaseManager.php';

class DatabaseManagerProxy Implements IDatabaseManager{
    private $realDatabaseManager;
    private $userRole; // User role (e.g., 'admin', 'employee', 'donor')

    public function __construct(string $userRole) {
        $this->userRole = $userRole;
        $this->realDatabaseManager = DatabaseManager::getInstance();
    }

    /**
     * Check if the user has permission to execute a query on a specific table.
     *
     * @param string $queryType The type of query (e.g., 'SELECT', 'INSERT', 'UPDATE', 'DELETE').
     * @param string $tableName The name of the table being accessed.
     * @return bool True if the user has permission, false otherwise.
     */
    private function hasPermission(string $queryType, string $tableName): bool {
        // Define permissions based on user roles and tables
        switch ($this->userRole) {
            case 'admin':
                // Admins can execute all types of queries on all tables
                return true;

            case 'donor':
                // Donors can INSERT, UPDATE, and SELECT on 'users' and 'donationHistory' tables
                $allowedTables = ['users', 'donationHistory'];
                if (in_array($tableName, $allowedTables)) {
                    return in_array($queryType, ['SELECT', 'INSERT', 'UPDATE']);
                }
                return false;

            case 'employee':
                // Employees can UPDATE and SELECT on 'employees' and 'appointments' tables
                $allowedTables = ['employees', 'appointments'];
                if (in_array($tableName, $allowedTables)) {
                    return in_array($queryType, ['SELECT', 'UPDATE']);
                }
                return false;

            default:
                // Unknown roles have no permissions
                return false;
        }
    }

    /**
     * Extract the table name from the SQL query.
     *
     * @param string $query The SQL query.
     * @return string The table name.
     */
    private function extractTableName(string $query): string {
        // Extract the table name from the query (simplified logic)
        $query = strtolower($query);
        $keywords = ['from', 'into', 'update', 'join'];
        foreach ($keywords as $keyword) {
            if (strpos($query, $keyword) !== false) {
                $parts = explode($keyword, $query);
                $tablePart = trim($parts[1]);
                $tableName = explode(' ', $tablePart)[0];
                return $tableName;
            }
        }
        throw new Exception("Unable to extract table name from query: $query");
    }

    /**
     * Execute a query if the user has permission.
     *
     * @param string $query The SQL query to execute.
     * @return mysqli_result|bool The result of the query, or false if the user doesn't have permission.
     */
    public function runQuery($query): mysqli_result|bool {
        // Extract the query type (e.g., SELECT, INSERT, UPDATE, DELETE)
        $queryType = strtoupper(explode(' ', trim($query))[0]);

        // Extract the table name from the query
        $tableName = $this->extractTableName($query);

        // Check if the user has permission to execute the query on the table
        if ($this->hasPermission($queryType, $tableName)) {
            return $this->realDatabaseManager->runQuery($query);
        } else {
            throw new Exception("Permission denied: User role '{$this->userRole}' cannot execute '$queryType' queries on table '$tableName'.");
        }
    }

    /**
     * Execute a SELECT query if the user has permission.
     *
     * @param string $query The SQL SELECT query to execute.
     * @return mysqli_result|bool The result of the query, or false if the user doesn't have permission.
     */
    public function run_select_query($query): mysqli_result|bool {
        // Extract the table name from the query
        $tableName = $this->extractTableName($query);

        // Check if the user has permission to execute SELECT queries on the table
        if ($this->hasPermission('SELECT', $tableName)) {
            return $this->realDatabaseManager->run_select_query($query);
        } else {
            throw new Exception("Permission denied: User role '{$this->userRole}' cannot execute SELECT queries on table '$tableName'.");
        }
    }

    /**
     * Get the last inserted ID if the user has permission.
     *
     * @return int The last inserted ID.
     */
    public function getLastInsertId(): int {
        // Only allow access to the last inserted ID if the user has INSERT permission on any table
        if ($this->userRole === 'admin' || $this->userRole === 'donor') {
            return $this->realDatabaseManager->getLastInsertId();
        } else {
            throw new Exception("Permission denied: User role '{$this->userRole}' cannot access the last inserted ID.");
        }
    }
}



// Helper function to display test results
function displayTestResult(string $testName, bool $passed): void {
    echo $testName . ": \n";
    if($passed)
        echo "PASSED        ";
    else 
        echo "FAILED         ";
}

// Test cases
try {
    // Test 1: Admin can execute any query
    $adminProxy = new DatabaseManagerProxy('admin');
    $adminProxy->runQuery("SELECT * FROM users"); // Should succeed
    displayTestResult("Admin SELECT query on 'users' table", true);
    echo "\n";

    // $adminProxy->runQuery("INSERT INTO users (name, email) VALUES ('Test Etsh32', 'etshTest32@example.com')"); // Should succeed
    // displayTestResult("Admin INSERT query on 'users' table", true);
    // echo "\n";

    // $adminProxy->runQuery("UPDATE users SET name = 'Updated Etsh2' WHERE id = 1"); // Should succeed
    // displayTestResult("Admin UPDATE query on 'users' table", true);
    // echo "\n";

    // $adminProxy->runQuery("DELETE FROM users WHERE id = 16"); // Should succeed
    // displayTestResult("Admin DELETE query on 'users' table", true);
    // echo "\n";

    // Test 2: Donor can execute SELECT, INSERT, and UPDATE on 'users' and 'donationHistory' tables
    // $donorProxy = new DatabaseManagerProxy('donor');
    // $donorProxy->runQuery("SELECT * FROM users"); // Should succeed
    // displayTestResult("Donor SELECT query on 'users' table", true);

    // $donorProxy->runQuery("INSERT INTO donationHistory (donor_id, amount) VALUES (1, 50.0)"); // Should succeed
    // displayTestResult("Donor INSERT query on 'donationHistory' table", true);

    // $donorProxy->runQuery("UPDATE donationHistory SET amount = 100.0 WHERE id = 1"); // Should succeed
    // displayTestResult("Donor UPDATE query on 'donationHistory' table", true);

    // try {
    //     $donorProxy->runQuery("DELETE FROM donationHistory WHERE id = 1"); // Should fail
    //     displayTestResult("Donor DELETE query on 'donationHistory' table", false);
    // } catch (Exception $e) {
    //     displayTestResult("Donor DELETE query on 'donationHistory' table", true);
    // }

    // Test 3: Employee can execute SELECT and UPDATE on 'employees' and 'appointments' tables
    // $employeeProxy = new DatabaseManagerProxy('employee');
    // $employeeProxy->runQuery("SELECT * FROM employees"); // Should succeed
    // displayTestResult("Employee SELECT query on 'employees' table", true);

    // $employeeProxy->runQuery("UPDATE appointments SET status = 'AppointmentTesttt' WHERE appointmentID = 1"); // Should succeed
    // displayTestResult("Employee UPDATE query on 'appointments' table", true);

    // try {   
    //     $employeeProxy->runQuery("INSERT INTO employees (role, department) VALUES ('kvmfmvkdm', 'Administrkmdskcmation')"); // Should fail
    // } catch (Exception $e) {
    //     displayTestResult("Employee INSERT query on 'employees' table", false);
    // }

    // try {
    //     $employeeProxy->runQuery("DELETE FROM appointments WHERE appointmentID = 1"); // Should fail
    // } catch (Exception $e) {
    //     displayTestResult("Employee DELETE query on 'appointments' table", false);
    // }

    // // Test 4: Unknown role has no permissions
    // $unknownProxy = new DatabaseManagerProxy('unknown');
    // try {
    //     $unknownProxy->runQuery("SELECT * FROM users"); // Should fail
    // } catch (Exception $e) {
    //     displayTestResult("Unknown role SELECT query on 'users' table", false);
    // }

    // // Test 5: Donor can access last inserted ID
    // $donorProxy->runQuery("INSERT INTO donationHistory (donor_id, amount) VALUES (2, 75.0)"); // Should succeed
    // $lastInsertId = $donorProxy->getLastInsertId(); // Should succeed
    // displayTestResult("Donor access to last inserted ID", $lastInsertId > 0);

    // // Test 6: Employee cannot access last inserted ID
    // try {
    //     $employeeProxy->getLastInsertId(); // Should fail
    //     displayTestResult("Employee access to last inserted ID", false);
    // } catch (Exception $e) {
    //     displayTestResult("Employee access to last inserted ID", true);
    // }

} catch (Exception $e) {
    echo "Test failed with exception: " . $e->getMessage() . "\n";
}
?>