<?php
require_once 'configs/database.php';

abstract class BaseModel {
    // Database connection
    protected static $_connection;

    public function __construct() {
        if (!isset(self::$_connection)) {
            self::$_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
            if (self::$_connection->connect_errno) {
                error_log("Database connection failed: " . self::$_connection->connect_error);
                die("Connect failed: " . self::$_connection->connect_error);
            }
            // Set charset to UTF-8
            self::$_connection->set_charset("utf8mb4");
        }
    }

    /**
     * Query in database
     * @param string $sql
     * @return mysqli_result|bool
     */
    protected function query($sql) {
        $result = self::$_connection->query($sql);
        if ($result === false) {
            error_log("Query failed: " . self::$_connection->error);
        }
        return $result;
    }

    /**
     * Select statement
     * @param string $sql
     * @return array
     */
    protected function select($sql) {
        $result = $this->query($sql);
        $rows = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Delete statement
     * @param string $sql
     * @return bool
     */
    protected function delete($sql) {
        return $this->query($sql);
    }

    /**
     * Update statement
     * @param string $sql
     * @return bool
     */
    protected function update($sql) {
        return $this->query($sql);
    }

    /**
     * Insert statement
     * @param string $sql
     * @return bool
     */
    protected function insert($sql) {
        return $this->query($sql);
    }

    /**
     * Get last inserted ID
     * @return int
     */
    protected function getLastInsertId() {
        return self::$_connection->insert_id;
    }

    /**
     * Prepare statement
     * @param string $sql
     * @return mysqli_stmt
     */
    protected function prepare($sql) {
        $stmt = self::$_connection->prepare($sql);
        if ($stmt === false) {
            error_log("Prepare failed: " . self::$_connection->error);
            die("Prepare failed: " . self::$_connection->error);
        }
        return $stmt;
    }

    /**
     * Close connection
     */
    public function __destruct() {
        if (self::$_connection) {
            self::$_connection->close();
        }
    }
}