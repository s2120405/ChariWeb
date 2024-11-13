<?php
date_default_timezone_set("Asia/Manila");


define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'delossantosd262_chariseek');

/**
 * Create a PDO database connection.
 *
 * @return PDO|null Returns a PDO connection or null if it fails.
 */
function getDbConnection() {
    try {
        $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        error_log("Database Connection Failed: " . $e->getMessage());
        return null;
    }
}
?>
