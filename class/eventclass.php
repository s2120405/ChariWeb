<?php
class Event {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'delossantosd262_chariseek';
    private $conn;

    public function __construct() {
        // Establish database connection
        try {
            $this->conn = new PDO("mysql:host=" . $this->DB_SERVER . ";dbname=" . $this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    public function new_event($title, $description, $start_date, $end_date, $start_time, $end_time, $latitude, $longitude, $location, $max_volunteers, $event_image, $points = 0, $event_status = 'upcoming', $created_by_admin, $categories = [], $organization_id = null) {
        // Generate a unique QR code with "EVNT" prefix
        do {
            $qr_code = 'EVNT' . bin2hex(random_bytes(5));
    
            // Check if this QR code already exists in the database
            $check_stmt = $this->conn->prepare("SELECT COUNT(*) FROM event WHERE qr_code = ?");
            $check_stmt->execute([$qr_code]);
            $qr_exists = $check_stmt->fetchColumn() > 0;
        } while ($qr_exists); // Repeat until a unique QR code is found
    
        // SQL query to insert a new event, with organization_id if provided
        $stmt = $this->conn->prepare("INSERT INTO event (
            title, description, start_date, end_date, start_time, end_time, latitude, longitude, location, 
            max_volunteers, event_image, points, event_status, created_by_admin, organization_id, qr_code, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    
        try {
            $this->conn->beginTransaction();
    
            // Set the organization_id if the user is an organization
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'organization') {
                $organization_id = $_SESSION['organization_id'];
            }
    
            // Execute the query with all fields
            $stmt->execute([
                $title, $description, $start_date, $end_date, $start_time, $end_time, $latitude, $longitude, 
                $location, $max_volunteers, $event_image, $points, $event_status, $created_by_admin, $organization_id, $qr_code
            ]);
    
            // Get the last inserted event ID
            $event_id = $this->conn->lastInsertId();
    
            // Insert category associations into the event_categories table
            if (!empty($categories)) {
                // Prepare the statement for inserting categories
                $category_stmt = $this->conn->prepare("INSERT INTO event_categories (event_id, category_id) VALUES (?, ?)");
    
                foreach ($categories as $category_id) {
                    // Check if the category_id exists in the category table before insertion
                    $check_category_stmt = $this->conn->prepare("SELECT COUNT(*) FROM category WHERE category_id = ?");
                    $check_category_stmt->execute([$category_id]);
    
                    if ($check_category_stmt->fetchColumn() > 0) {
                        // Insert into event_categories if category exists
                        $category_stmt->execute([$event_id, $category_id]);
                    } else {
                        // Optionally, handle the case where the category does not exist
                        error_log("Category ID {$category_id} does not exist, skipping.");
                    }
                }
            }
    
            // Define the QR code file path
            $qr_image_path = '../qrcodes/' . $qr_code . '_qrcode.png';
    
            // Generate the QR code image using the unique value with "EVNT" prefix
            QRcode::png($qr_code, $qr_image_path);
    
            // Commit the transaction
            $this->conn->commit();
    
            return true;
    
        } catch (Exception $e) {
            // Rollback the transaction if something goes wrong
            $this->conn->rollback();
            throw $e;
        }
    }
    

    public function update_event($event_id, $title, $description, $start_date, $end_date, $start_time, $end_time, $latitude, $longitude, $location, $max_volunteers, $event_image = null, $points = 0, $event_status = 'upcoming', $created_by_admin = false, $categories = []) {
        try {
            // Begin the transaction
            $this->conn->beginTransaction();
            
            // Prepare SQL query to update event details
            $stmt = $this->conn->prepare("UPDATE event SET
                title = ?, description = ?, start_date = ?, end_date = ?, start_time = ?, end_time = ?, 
                latitude = ?, longitude = ?, location = ?, max_volunteers = ?, points = ?, 
                event_status = ?, created_by_admin = ?, updated_at = NOW()
                WHERE event_id = ?");
            
            // Execute the update query with provided data
            $stmt->execute([
                $title, $description, $start_date, $end_date, $start_time, $end_time,
                $latitude, $longitude, $location, $max_volunteers, $points, 
                $event_status, $created_by_admin, $event_id
            ]);
    
            // Handle event image if provided
            if ($event_image) {
                $image_stmt = $this->conn->prepare("UPDATE event SET event_image = ? WHERE id = ?");
                $image_stmt->execute([$event_image, $event_id]);
            }
    
            // Update category associations in the event_categories table
            $this->conn->prepare("DELETE FROM event_categories WHERE event_id = ?")->execute([$event_id]); // Remove existing categories
            
            if (!empty($categories)) {
                // Prepare the statement for inserting categories
                $category_stmt = $this->conn->prepare("INSERT INTO event_categories (event_id, category_id) VALUES (?, ?)");
                foreach ($categories as $category_id) {
                    // Check if the category_id exists in the category table before insertion
                    $check_category_stmt = $this->conn->prepare("SELECT COUNT(*) FROM category WHERE category_id = ?");
                    $check_category_stmt->execute([$category_id]);
                    
                    if ($check_category_stmt->fetchColumn() > 0) {
                        // Insert into event_categories if category exists
                        $category_stmt->execute([$event_id, $category_id]);
                    } else {
                        // Optionally log if category does not exist
                        error_log("Category ID {$category_id} does not exist, skipping.");
                    }
                }
            }
            
            // Commit the transaction
            $this->conn->commit();
    
            return true;
    
        } catch (Exception $e) {
            // Rollback the transaction if something goes wrong
            $this->conn->rollback();
            throw $e;
        }
    }
    
    
    public function is_qr_code_unique($qr_code) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Event WHERE qr_code = ?");
        $stmt->execute([$qr_code]);
        return $stmt->fetchColumn() == 0;
    }

    public function get_events() {
        // Check if the user is an admin or an organization
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            // Admin: Retrieve all events
            $stmt = $this->conn->prepare("SELECT * FROM event ORDER BY start_date ASC");
            $stmt->execute();
        } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'organization') {
            // Organization: Retrieve only events that belong to the logged-in organization
            $stmt = $this->conn->prepare("SELECT * FROM event WHERE organization_id = :organization_id ORDER BY start_date ASC");
            $stmt->bindParam(':organization_id', $_SESSION['organization_id'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            // Handle the case if the role is not set or is invalid
            return []; // Return an empty array or handle as needed
        }
        
        // Fetch all events
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Get the current date
        $currentDate = new DateTime();
    
        // Loop through the events and update their status
        foreach ($events as &$event) {
            $startDate = new DateTime($event['start_date']);
            $endDate = new DateTime($event['end_date']);
            
            // Determine the status based on the current date
            if ($currentDate > $endDate) {
                $event['event_status'] = 'Completed';
            } elseif ($currentDate >= $startDate && $currentDate <= $endDate) {
                $event['event_status'] = 'Ongoing';
            } else {
                $event['event_status'] = 'Upcoming';
            }
    
            // Update the event status in the database
            $this->updateEventStatus($event['event_id'], $event['event_status']);
        }
    
        return $events;
    }
    
    public function updateEventStatus($eventId, $status) {
        // Get the database connection
        $conn = getDbConnection();
        $stmt = $conn->prepare("UPDATE event SET event_status = :status WHERE event_id = :event_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':event_id', $eventId);
        $stmt->execute();
    }
    


    
    
}

