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
        $this->conn = new mysqli($this->host, $this->username);
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
    function runQuery($query, $echo = false) {
        if ($echo) echo '<pre>' . $query . '</pre>';
        if ($echo) echo $this->conn->query($query) === TRUE ? "Query ran successfully<br/>" : "Error: " . $this->conn->error;
        if ($echo) echo "<hr/>";
    }

    // Method to get the last inserted ID
    // public function getLastInsertId(): string {
    //     return $this->conn->lastInsertId();
    // }
    
    // Method to execute a select query with optional echoing for debugging
    public function run_select_query($query, $echo = false): mysqli_result|bool {
        global $conn;
        $result = $conn->query($query);
        if ($echo) {
            echo '<pre>' . $query . '</pre>';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc())
                    echo $row;
            } else {
                echo "0 results";
            }
            echo "<hr/>";
        }
        return $result;
    }

    // Method to run multiple queries
    public function run_queries($queries, $echo = false): array {
        global $conn;
        $ret = [];
        foreach ($queries as $query) {
            $ret += [$conn->query($query)];
            if ($echo) {
                echo '<pre>' . $query . '</pre>';
                echo $ret[array_key_last($ret)] === TRUE ? "Query ran successfully<br/>" : "Error: " . $conn->error;
                echo "<hr/>";
            }
        }
        return $ret;
    }
}
?>
