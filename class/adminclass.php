<?php
session_start(); // Start a session for login state

class Admin {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'delossantosd262_chariseek';
    private $conn;

    public function __construct() {
        // Establish database connection
        try {
            $this->conn = new PDO("mysql:host=$this->DB_SERVER;dbname=$this->DB_DATABASE", $this->DB_USERNAME, $this->DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
    }

    // Login function
    
public function login($email, $password) {
    $stmt = $this->conn->prepare("SELECT * FROM admin WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if account exists
    if ($admin) {
        echo "Account found for email: " . $email;

        // Verify password with hashed version from the database
        if (password_verify($password, $admin['password'])) {
            echo "Password is correct!";
            $_SESSION['loggedin'] = true;
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['name'] = $admin['name'];
            $_SESSION['role'] = 'admin';
            return true;
        } else {
            echo "Password is incorrect!";
        }
    } else {
        echo "No account found with this email!";
    }
    return false; // Invalid login
}

    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'];
    }

    // Register User
    public function checkEmailExists($email) {
        $stmt = $this->conn->prepare("SELECT * FROM admin WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false; // Returns true if email exists
    }
    
    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    
        $stmt = $this->conn->prepare("INSERT INTO admin (name, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
    
        return $stmt->execute(); // Returns true if insert was successful
    }

    // Logout function
    public function logout() {
        session_unset();
        session_destroy();
    }
}
?>
