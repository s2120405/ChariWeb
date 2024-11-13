<?php
// Database connection
$DB_SERVER = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = '';
$DB_DATABASE = 'delossantosd262_chariseek';  // Replace with your actual database name

$events = []; // Default empty array

try {
    $conn = new PDO("mysql:host=$DB_SERVER;dbname=$DB_DATABASE", $DB_USERNAME, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch events
    $stmt = $conn->prepare("SELECT title, start_date AS start, end_date AS end, description FROM event");
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Format the event data as needed by FullCalendar
        $events[] = [
            'title' => $row['title'],
            'start' => $row['start'],
            'end' => $row['end'] ?? $row['start'],  // Use start date as end date if end is null
            'description' => $row['description'] ?? 'No description'
        ];
    }

} catch (PDOException $e) {
    $events = ['error' => $e->getMessage()]; // Handle database errors gracefully
}

// Return the events as a JSON response
// This will be used by the JavaScript code below
$eventsJson = json_encode($events);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Calendar</title>
    
    <!-- FullCalendar v3.x CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        #calendar {
            width: 100%;
            display: ;
        }
        #no-event-message {
            text-align: center;
            color: #888;
            display: none;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Event Calendar</h2>
    
    <!-- No event message -->
    <div id="no-event-message">No events available.</div>
    
    <!-- FullCalendar container -->
    <div id="calendar"></div>

    <!-- FullCalendar v3.x JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var noEventMessage = document.getElementById('no-event-message'); // "No event" message

            // Events from the PHP backend (already encoded into the $eventsJson variable)
            var events = <?php echo $eventsJson; ?>;

            // Check if there are events
            if (events.length === 0) {
                noEventMessage.style.display = 'block'; // Show message if no events
            } else {
                noEventMessage.style.display = 'none'; // Hide message if there are events
            }

            // Initialize FullCalendar (v3.x) using jQuery
            $(calendarEl).fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: events, // Dynamically pass events from PHP
                eventClick: function(event, jsEvent, view) {
                    alert('Event: ' + event.title + '\nDescription: ' + (event.description || 'No description'));
                },
                height: 'auto',  // Adjust calendar height to content
            });
        });
    </script>
</body>
</html>
