<?php
include 'config.php'; // Include your configuration file for DB connection

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #fff;
            padding: 20px;
            width: 95%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-sizing: border-box;
            position: relative;
            border-radius: 8px;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }
        #map {
            width: 100%;
            height: 400px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Manage Events</h1>

    <!-- Button to open the event creation modal -->
    <button onclick="openEventModal()">Add New Event</button>

    <!-- Event List -->
    <h2>Existing Events</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Location</th>
                <th>Status</th>
                <th>Max Volunteers</th>
                <th>Points</th>
                <th>QR Code</th>
                <th>View Map</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once 'class/eventclass.php';
            $eventClass = new Event();
            $events = $eventClass->get_Events();

            foreach ($events as $event) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($event['title']) . "</td>";
                echo "<td>" . htmlspecialchars($event['description']) . "</td>";
                echo "<td>" . htmlspecialchars($event['start_date']) . "</td>";
                echo "<td>" . htmlspecialchars($event['end_date']) . "</td>";
                echo "<td>" . htmlspecialchars($event['location']) . "</td>";
                echo "<td>" . htmlspecialchars($event['event_status']) . "</td>";
                echo "<td>" . htmlspecialchars($event['max_volunteers']) . "</td>";
                echo "<td>" . htmlspecialchars($event['points']) . "</td>";
                echo "<td><button onclick='showQRCode(\"" . htmlspecialchars($event['qr_code']) . "\")'>View QR Code</button></td>";
                echo "<td><button onclick='viewMap(" . htmlspecialchars($event['latitude']) . ", " . htmlspecialchars($event['longitude']) . ")'>View Map</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- New Map Display Modal -->
    <div id="mapDisplayModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeMapDisplayModal()">&times;</span>
        <h3>Event Location Map</h3>
        <div id="eventMap" style="width: 100%; height: 400px;"></div>
        <button onclick="closeMapDisplayModal()">Close</button>
    </div>
</div>


    <!-- QR Code Modal -->
    <div id="qrCodeModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeQRCodeModal()">&times;</span>
            <h3>Event QR Code</h3>
            <img id="qrCodeImage" src="" alt="QR Code" style="width: 200px; height: 200px;">
            <br><br>
            <button onclick="closeQRCodeModal()">Close</button>
        </div>
    </div>

    <!-- Create Event Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeEventModal()">&times;</span>
            <h2>Add a New Event</h2>

            <form method="post" action="processes/process.event.php?action=new" enctype="multipart/form-data">
                <label for="title">Event Name (Title):</label>
                <input type="text" id="title" name="title" required>

                <label for="description">Event Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label>Categories:</label>
                <div class="category-group">
                    <?php foreach ($categories as $category): ?>
                        <label>
                            <input type="checkbox" name="categories[]" value="<?php echo htmlspecialchars($category['category_id']); ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>

                <label for="start_time">Start Time:</label>
                <input type="time" id="start_time" name="start_time" required>

                <label for="end_time">End Time:</label>
                <input type="time" id="end_time" name="end_time" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <label for="latitude">Latitude:</label>
                <input type="text" id="latitude" name="latitude" required readonly>

                <label for="longitude">Longitude:</label>
                <input type="text" id="longitude" name="longitude" required readonly>

                <div id="map"></div>

                <label for="max_volunteers">Max Volunteers:</label>
                <input type="number" id="max_volunteers" name="max_volunteers" required min="1">

                <label for="points">Event Points:</label>
                <input type="number" id="points" name="points" required min="0">

                <label for="event_image">Event Image:</label>
                <input type="file" id="event_image" name="event_image" accept="image/*" required>

                <button type="submit">Add Event</button>
            </form>
        </div>
    </div>

    <script>
    // Map setup
    var map = L.map('map').setView([10.6767, 122.9567], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var marker = L.marker([10.6767, 122.9567], {draggable: true}).addTo(map);

    function updateMarkerInputs(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    updateMarkerInputs(marker.getLatLng().lat, marker.getLatLng().lng);

    marker.on('dragend', function () {
        var position = marker.getLatLng();
        updateMarkerInputs(position.lat, position.lng);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateMarkerInputs(e.latlng.lat, e.latlng.lng);
    });

    // Modal functions
    function openEventModal() {
        document.getElementById("eventModal").style.display = "flex";
        setTimeout(() => { map.invalidateSize(); }, 100);
    }

    function closeEventModal() {
        document.getElementById("eventModal").style.display = "none";
    }

    function openQRCodeModal() {
        document.getElementById("qrCodeModal").style.display = "flex";
    }

    function closeQRCodeModal() {
        document.getElementById("qrCodeModal").style.display = "none";
    }

    function showQRCode(qrCode) {
        document.getElementById("qrCodeImage").src = `qrcodes/${qrCode}_qrcode.png`;
        openQRCodeModal();
    }

// Function to view map for an event
function viewMap(latitude, longitude) {
    // Get the map container
    var mapContainer = document.getElementById('eventMap');
    if (!mapContainer) {
        console.error("Map container not found");
        return;
    }

    // Ensure the map container is cleared before initializing the map again
    mapContainer.innerHTML = '';  // This clears the previous map if any

    // Initialize the map inside the map modal
    var eventMap = L.map('eventMap').setView([latitude, longitude], 13);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(eventMap);

    // Add a marker at the event's location
    L.marker([latitude, longitude]).addTo(eventMap);

    // Open the map display modal
    openMapDisplayModal();

    // Call invalidateSize() after the modal is opened to fix map rendering
    setTimeout(() => { 
        eventMap.invalidateSize(); 
    }, 100);

    // Store the eventMap in a global variable so it can be accessed when closing the modal
    window.currentMap = eventMap; 
}

// Function to open the map modal
function openMapDisplayModal() {
    document.getElementById("mapDisplayModal").style.display = "flex";
}

// Function to close the map modal
function closeMapDisplayModal() {
    document.getElementById("mapDisplayModal").style.display = "none";

    // Cleanup the map by clearing its contents after closing
    var mapContainer = document.getElementById("eventMap");
    if (mapContainer) {
        mapContainer.innerHTML = ''; // This clears the map container
    }

    // Destroy the Leaflet map if it exists
    if (window.currentMap) {
        window.currentMap.remove();  // This ensures the map is properly destroyed
        window.currentMap = null; // Reset the global map variable
    }
}

// Modal close on clicking outside
window.onclick = function(event) {
    const mapDisplayModal = document.getElementById("mapDisplayModal");
    if (event.target == mapDisplayModal) {
        closeMapDisplayModal();
    }
}
</script>
</body>
</html>
