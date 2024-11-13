<?php
class Category {
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

    // Function to create a new category
    public function new_category($category_name, $category_desc = '') {
        // Ensure the category name is unique
        $check_stmt = $this->conn->prepare("SELECT COUNT(*) FROM category WHERE category_name = ?");
        $check_stmt->execute([$category_name]);
        $exists = $check_stmt->fetchColumn() > 0;
    
        if ($exists) {
            throw new Exception("Category '{$category_name}' already exists.");
        }
    
        // SQL query to insert a new category
        $stmt = $this->conn->prepare("INSERT INTO category (category_name, category_desc) VALUES (?, ?)");
    
        try {
            // Begin transaction
            $this->conn->beginTransaction();
    
            // Execute the query to insert the new category
            $stmt->execute([$category_name, $category_desc]);
    
            // Commit the transaction
            $this->conn->commit();
    
            return true;
    
        } catch (Exception $e) {
            // Rollback the transaction if something goes wrong
            $this->conn->rollback();
            throw $e;
        }
    }

    // Function to retrieve all categories
    public function get_categories() {
        $stmt = $this->conn->prepare("SELECT * FROM Category");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to delete a category
    // In categoryclass.php
    public function delete_category($category_id) {
        $stmt = $this->conn->prepare("DELETE FROM Category WHERE category_id = ?");

    try {
        $stmt->execute([$category_id]);
        return true; // Return true if category deletion succeeds
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        throw new Exception("Failed to delete category: " . $e->getMessage());
    }
}

}

?>

