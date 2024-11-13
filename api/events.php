<?php
// Database connection
$DB_SERVER = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = '';
$DB_DATABASE = 'delossantosd262_chariseek';  // Replace with your actual database name

try {
    $conn = new PDO("mysql:host=$DB_SERVER;dbname=$DB_DATABASE", $DB_USERNAME, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch events
    $stmt = $conn->prepare("SELECT title, start_date AS start, end_date AS end, description FROM event");
    $stmt->execute();
    
    $events = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Format the event data as needed by FullCalendar
        $events[] = [
            'title' => $row['title'],
            'start' => $row['start'],
            'end' => $row['end'] ?? $row['start'],  // Handle cases where 'end' is null
            'description' => $row['description'] ?? 'No description'
        ];
    }

    // Return events as JSON
    echo json_encode($events);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>