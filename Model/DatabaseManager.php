<?php
class DatabaseManager {
    // Private static instance of DatabaseManager
    private static ?DatabaseManager $instance = null;

    // Database connection properties
    private ?PDO $connection = null; //PHP data object
    private string $host;
    private string $username;
    private string $password;
    private string $database;

    // Private constructor to prevent multiple instances
    private function __construct() {
        // Set database credentials (these can be modified as needed)
        $this->host = 'localhost';
        $this->username = 'root';
        $this->password = '';
        $this->database = 'my_database';

        // Establish database connection
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database}";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Public static method to get the single instance of DatabaseManager
    public static function getInstance(): DatabaseManager {
        if (self::$instance === null) {
            self::$instance = new DatabaseManager();
        }
        return self::$instance;
    }

    // Method to get the PDO connection
    public function getConnection(): ?PDO {
        return $this->connection;
    }

    // Method to execute a SQL query
    private function runQuery(string $sqlQuery): void {
        try {
            $this->connection->exec($sqlQuery);
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
        }
    }
    private function run_select_query($query, $echo = false): mysqli_result|bool
{
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
    private function run_queries($queries, $echo = false): array
    {
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

//$configs = require "config.php";
//$conn = new mysqli($configs->DB_HOST, $configs->DB_USER);
//
//if ($conn->connect_error) {
//    die("Connection failed: " . $conn->connect_error);
//}
//
//echo "Connected successfully<br/><hr/>";
//

//
//function run_query($query, $echo = false): bool
//{
//    return run_queries([$query], $echo)[0];
//}
//

    


// $conn->close();
