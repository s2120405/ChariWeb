<?php
require_once 'class/reportclass.php';

// Instantiate the Report class
$reportClass = new Report();
$reports = $reportClass->getPendingUncleanReports();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Management</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        .map {
            width: 100%;
            height: 300px;
        }
        .report-image {
            max-width: 100%;
            height: 650px;
            display: inline;
            margin-top: 10px;
        }
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
            width: 90%;
            max-width: 600px;
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            cursor: pointer;
        }
        .open-modal-btn {
            padding: 8px 12px;
            margin-top: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Manage Unclean Reports</h1>

    <?php if (count($reports) > 0): ?>
        <?php foreach ($reports as $report): ?>
            <div class="report">
                <h3>Report ID: <?php echo $report['report_id']; ?></h3>
                <p>Description: <?php echo htmlspecialchars($report['description']); ?></p>
                <p>Status: <?php echo htmlspecialchars($report['status']); ?></p>
                <p>Address: <?php echo htmlspecialchars($report['address']); ?></p>
                <p>Report Date: <?php echo htmlspecialchars($report['report_date']); ?></p>

                <!-- Buttons to open separate modals for image and map -->
                <?php if (!empty($report['image_url'])): ?>
                    <button class="open-modal-btn" onclick="openImageModal(<?php echo $report['report_id']; ?>)">View Image</button>
                <?php endif; ?>
                <?php if (!empty($report['latitude']) && !empty($report['longitude'])): ?>
                    <button class="open-modal-btn" onclick="openMapModal(<?php echo $report['report_id']; ?>)">View Map</button>
                <?php endif; ?>

                <!-- Image Modal -->
                <div id="image-modal-<?php echo $report['report_id']; ?>" class="modal" onclick="outsideClickClose(event, <?php echo $report['report_id']; ?>, 'image')">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeModal(<?php echo $report['report_id']; ?>, 'image')">&times;</span>
                        <img src="<?php echo htmlspecialchars($report['image_url']); ?>" alt="Report Image" class="report-image">
                    </div>
                </div>

                <!-- Map Modal -->
                <div id="map-modal-<?php echo $report['report_id']; ?>" class="modal" onclick="outsideClickClose(event, <?php echo $report['report_id']; ?>, 'map')">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeModal(<?php echo $report['report_id']; ?>, 'map')">&times;</span>
                        <div id="map-<?php echo $report['report_id']; ?>" class="map"></div>
                    </div>
                </div>

                <!-- Form to update report status -->
                <form action="processes/process.report.php?action=updaterep" method="POST">
                    <input type="hidden" name="report_id" value="<?php echo $report['report_id']; ?>">
                    <label for="status">Update Status:</label>
                    <select name="status">
                        <option value="pending" <?php echo $report['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="resolved" <?php echo $report['status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                        <option value="declined" <?php echo $report['status'] == 'declined' ? 'selected' : ''; ?>>Declined</option>
                    </select>
                    <button type="submit" name="action">Update Report</button>
                </form>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No unclean reports found.</p>
    <?php endif; ?>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        function openImageModal(reportId) {
            document.getElementById('image-modal-' + reportId).style.display = 'flex';
        }

        function openMapModal(reportId) {
            document.getElementById('map-modal-' + reportId).style.display = 'flex';

            var latitude = <?php echo json_encode(array_column($reports, 'latitude', 'report_id')); ?>;
            var longitude = <?php echo json_encode(array_column($reports, 'longitude', 'report_id')); ?>;
            var mapId = 'map-' + reportId;

            if (!document.getElementById(mapId).hasChildNodes()) {
                var map = L.map(mapId).setView([latitude[reportId], longitude[reportId]], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
                L.marker([latitude[reportId], longitude[reportId]]).addTo(map)
                    .bindPopup('<strong>Report Location</strong>')
                    .openPopup();
            }
        }

        function closeModal(reportId, modalType) {
            document.getElementById(modalType + '-modal-' + reportId).style.display = 'none';
        }

        function outsideClickClose(event, reportId, modalType) {
            const modal = document.getElementById(modalType + '-modal-' + reportId);
            if (event.target === modal) {
                closeModal(reportId, modalType);
            }
        }
    </script>
</body>
</html>

