<?php
include '../config.php'; // Include your configuration file for DB connection

// Fetch categories from the database
try {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT category_id, category_name FROM category");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching categories: " . htmlspecialchars($e->getMessage());
    exit(); // Stop execution on error
}

// Fetch event details by ID for editing
$event_id = $_GET['event_id']; // Event ID passed as a query parameter
try {
    $stmt = $conn->prepare("SELECT * FROM event WHERE event_id = ?");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Fetch associated categories for the event
    $stmt = $conn->prepare("SELECT category_id FROM event_categories WHERE event_id = ?");
    $stmt->execute([$event_id]);
    $selected_categories = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'category_id');
    
} catch (PDOException $e) {
    echo "Error fetching event details: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
    <h1>Edit Event</h1>

    <div class="form-wrapper">
        <form method="post" action="../processes/process.event.php?action=editevent" enctype="multipart/form-data">
            <!-- Hidden field for event ID -->
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['event_id']); ?>">

            <label for="title">Event Name (Title):</label>
            <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($event['title']); ?>">

            <label for="description">Event Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>

            <label>Categories:</label>
            <div class="category-group">
                <?php foreach ($categories as $category): ?>
                    <label>
                        <input type="checkbox" name="categories[]" value="<?php echo htmlspecialchars($category['category_id']); ?>" 
                            <?php echo in_array($category['category_id'], $selected_categories) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="form-group">
                <div>
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required value="<?php echo htmlspecialchars($event['start_date']); ?>">
                </div>
                <div>
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required value="<?php echo htmlspecialchars($event['end_date']); ?>">
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label for="start_time">Start Time:</label>
                    <input type="time" id="start_time" name="start_time" required value="<?php echo htmlspecialchars($event['start_time']); ?>">
                </div>
                <div>
                    <label for="end_time">End Time:</label>
                    <input type="time" id="end_time" name="end_time" required value="<?php echo htmlspecialchars($event['end_time']); ?>">
                </div>
            </div>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required value="<?php echo htmlspecialchars($event['location']); ?>">

            <label for="latitude">Latitude:</label>
            <input type="text" id="latitude" name="latitude" placeholder="Latitude" required readonly value="<?php echo htmlspecialchars($event['latitude']); ?>">

            <label for="longitude">Longitude:</label>
            <input type="text" id="longitude" name="longitude" placeholder="Longitude" required readonly value="<?php echo htmlspecialchars($event['longitude']); ?>">

            <!-- Map Div -->
            <div id="map" style="height: 400px; margin-bottom: 20px;"></div>

            <label for="max_volunteers">Max Volunteers:</label>
            <input type="number" id="max_volunteers" name="max_volunteers" required min="1" value="<?php echo htmlspecialchars($event['max_volunteers']); ?>">

            <label for="points">Event Points:</label>
            <input type="number" id="points" name="points" required min="0" value="<?php echo htmlspecialchars($event['points']); ?>">

            <label for="event_image">Event Image:</label>
            <input type="file" id="event_image" name="event_image" accept="image/*">
            <?php if (!empty($event['event_image'])): ?>
                <p>Current Image: <a href="../uploads/<?php echo htmlspecialchars(basename($event['event_image'])); ?>" target="_blank">View</a></p>
            <?php endif; ?>

            <button type="submit">Update Event</button>
        </form>
    </div>

    <script src="../javascript.js"></script>
    <script>
    // Initialize the map with the event's location
    var map = L.map('map').setView([<?php echo $event['latitude']; ?>, <?php echo $event['longitude']; ?>], 13);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add a draggable marker at the event's location
    var marker = L.marker([<?php echo $event['latitude']; ?>, <?php echo $event['longitude']; ?>], {draggable: true}).addTo(map);

    // Update the input fields with the marker's position
    function updateMarkerInputs(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    // Initialize the inputs with the current marker position
    updateMarkerInputs(marker.getLatLng().lat, marker.getLatLng().lng);

    // Listen for drag events on the marker
    marker.on('dragend', function () {
        var position = marker.getLatLng();
        updateMarkerInputs(position.lat, position.lng);
    });

    // Allow users to click on the map to move the marker
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateMarkerInputs(e.latlng.lat, e.latlng.lng);
    });
    </script>
</body>
</html>
