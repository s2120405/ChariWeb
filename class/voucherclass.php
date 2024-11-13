<?php
class Voucher {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'delossantosd262_chariseek';
    private $conn;

    // Constructor to establish the database connection
    public function __construct() {
        $this->conn = new mysqli($this->DB_SERVER, $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_DATABASE);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Method to get all vouchers
    public function getAllVouchers() {
        $query = "SELECT * FROM voucher";
        $result = $this->conn->query($query);

        $vouchers = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $vouchers[] = $row;
            }
        }

        return $vouchers;
    }

    // Method to create a new voucher
    public function createVoucher($voucher_title, $description, $points_required, $expiration_date ) {
        // Prepare the query to insert the new voucher
        $query = "INSERT INTO voucher (voucher_title, description, points_required, expiration_date) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Bind the parameters to the query
        $stmt->bind_param("ssis", $voucher_title, $description, $points_required, $expiration_date);

        // Execute the query and check if the insertion was successful
        if ($stmt->execute()) {
            return true;  // Voucher created successfully
        } else {
            return false;  // Failed to create voucher
        }
    }

    // Destructor to close the database connection
    public function __destruct() {
        $this->conn->close();
    }
}
