<?php
require_once 'configs/database.php';

abstract class BaseModel {
    // Database connection
    protected static $_connection;

    public function __construct() {

        if (!isset(self::$_connection)) {
            // ❌ Cách cũ: tạo $conn rồi không gán cho $_connection
            // $conn = mysqli_connect('127.0.0.1', 'root', '', 'app_web1', 3306);
            // self::$_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
            // if (self::$_connection->connect_errno) {
            //     printf("Connect failed");
            //     exit();
            // }

            // ✅ Cách mới: gán thẳng vào self::$_connection
            self::$_connection = mysqli_connect('127.0.0.1', 'root', '', 'app_web1', 3306);

            if (!self::$_connection) {
                die('MySQL connect failed (' . mysqli_connect_errno() . '): ' . mysqli_connect_error());
            }

            mysqli_set_charset(self::$_connection, 'utf8mb4');
        }

    }

    /**
     * Query in database
     * @param $sql
     */
    protected function query($sql) {
        $result = self::$_connection->query($sql);
        return $result;
    }

    /**
     * Select statement
     * @param $sql
     */
    protected function select($sql) {
        $result = $this->query($sql);
        $rows = [];
        if (!empty($result)) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Delete statement
     * @param $sql
     * @return mixed
     */
    protected function delete($sql) {
        $result = $this->query($sql);
        return $result;
    }

    /**
     * Update statement
     * @param $sql
     * @return mixed
     */
    protected function update($sql) {
        $result = $this->query($sql);
        return $result;
    }

    /**
     * Insert statement
     * @param $sql
     */
    protected function insert($sql) {
        $result = $this->query($sql);
        return $result;
    }

}