<?php
class DatabaseManager {
    // Private static instance of DatabaseManager
    private static ?DatabaseManager $instance = null;

    // Database connection properties
    private $conn;
    private string $host;
    private string $username;
    private string $password;
    private string $database;

    // Private constructor to prevent multiple instances
    private function __construct() {
        // Set database credentials
        $this->host = 'localhost';
        $this->username = 'root';
        $this->password = '';
        $this->database = 'food_donation';

        // Establish database connection
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Public static method to get the single instance of DatabaseManager
    public static function getInstance(): DatabaseManager {
        if (self::$instance === null) {
            self::$instance = new DatabaseManager();
        }
        return self::$instance;
    }

    // Method to execute a SQL query
    function runQuery($query) {
        return $this->conn->query($query);
    }

    // Get the last inserted ID
    public function getLastInsertId(): int {
        return $this->conn->insert_id;
    }

    // Method to prepare a query and bind parameters for select queries
    public function prepareAndExecute(string $query, array $params, string $types): mysqli_result|bool {
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $this->conn->error);
        }

        // Bind the parameters to the query
        $stmt->bind_param($types, ...$params);

        // Execute the statement
        $stmt->execute();

        return $stmt->get_result(); // Return the result
    }

    public function run_select_query($query): mysqli_result|bool {
        global $conn;
        $result = $conn->query($query);
        return $result;
    }
    
    // Method to run multiple queries
    public function run_queries($queries): array {
        $ret = [];
        foreach ($queries as $query) {
            $ret[] = $this->conn->query($query);
        }
        return $ret;
    }
}

?>