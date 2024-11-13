<?php
class Report {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'delossantosd262_chariseek';
    private $conn;

    public function __construct() {
        // Establish the database connection
        $this->conn = new mysqli($this->DB_SERVER, $this->DB_USERNAME, $this->DB_PASSWORD, $this->DB_DATABASE);

        // Check the connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Fetch all unclean reports
    public function getPendingUncleanReports() {
        // Get only reports where status is 'pending'
        $query = "SELECT * FROM unclean_reports WHERE status = 'pending'";
        $result = $this->conn->query($query);
    
        $reports = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reports[] = $row;
            }
        }
        return $reports;
    }
    

    // Update report status and award points if resolved
    public function updateReportStatus($report_id, $status) {
        // Update the status of the report
        $query = "UPDATE unclean_reports SET status = ? WHERE report_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $report_id);
        $stmt->execute();

        // If status is 'resolved', award points to the volunteer
        if ($status == 'resolved') {
            $this->rewardVolunteerPoints($report_id);
        }
        
        return $stmt->affected_rows > 0;
    }

   // Reward points to the volunteer for resolved reports
public function rewardVolunteerPoints($report_id) {
    // Get volunteer ID associated with the report
    $query = "SELECT volunteer_id FROM unclean_reports WHERE report_id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();

    if ($report) {
        $volunteer_id = $report['volunteer_id'];
        $points = 10;  // Define points awarded for reporting (e.g., 5 points)

        // Update volunteer points
        $updateQuery = "UPDATE volunteer SET points = points + ? WHERE volunteer_id = ?";
        $updateStmt = $this->conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $points, $volunteer_id);
        $updateStmt->execute();
    }
}


    // Close the database connection
    public function __destruct() {
        $this->conn->close();
    }
}

