<?php

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            try {
                self::$connection = new mysqli(
                    'localhost', // server location
                    'root',      // username
                    '',          // password
                    'web'        // database name
                );

                // check connection
                if (mysqli_connect_errno()) {
                    throw new Exception('Failed to connect to the database.');
                }
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

        return self::$connection;
    }
}
?>