<?php
class Organization {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'delossantosd262_chariseek';
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->DB_SERVER . ";dbname=" . $this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function orglogin($email, $password) {
        $stmt = $this->conn->prepare("SELECT organization_id, name, password FROM organization WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $organization = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Check if the organization account exists
        if ($organization && password_verify($password, $organization['password'])) {
            // Set session variables for successful login
            $_SESSION['loggedin'] = true;
            $_SESSION['organization_id'] = $organization['organization_id'];
            $_SESSION['organization_name'] = $organization['name'];
            $_SESSION['role'] = 'organization'; // Identify user role as organization
    
            return true; // Successful login
        }
    
        return false; // Invalid login credentials
    }

    // Method to register a new organization
    public function registerOrganization($name, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO organization (name, email, password) VALUES (:name, :email, :password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error registering organization: " . htmlspecialchars($e->getMessage());
            return false;
        }
    }

    // Method to authenticate organization login
    public function authenticateOrganization($email, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT organization_id, name, password FROM organization WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $organization = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($organization && password_verify($password, $organization['password'])) {
                return $organization; // Return organization details on success
            } else {
                return false; // Invalid email/password
            }
        } catch (PDOException $e) {
            echo "Error authenticating organization: " . htmlspecialchars($e->getMessage());
            return false;
        }
    }

    // Method to fetch organization details
    public function getOrganizationDetails($organization_id) {
        try {
            $stmt = $this->conn->prepare("SELECT organization_id, name, email FROM organization WHERE organization_id = :organization_id");
            $stmt->bindParam(':organization_id', $organization_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching organization details: " . htmlspecialchars($e->getMessage());
            return false;
        }
    }

    // Method to update organization details
    public function updateOrganization($organization_id, $name, $email) {
        try {
            $stmt = $this->conn->prepare("UPDATE organization SET name = :name, email = :email WHERE organization_id = :organization_id");
            $stmt->bindParam(':organization_id', $organization_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error updating organization: " . htmlspecialchars($e->getMessage());
            return false;
        }
    }

    // Method to close the database connection
    public function closeConnection() {
        $this->conn = null;
    }
}

