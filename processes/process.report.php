<?php
include '../class/reportclass.php'; // Ensure the Report class file is included

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'updaterep':
        process_report_update();
        break;
}

function process_report_update() {
    // Instantiate the Report class
    $report = new Report();

    // Retrieve and sanitize POST data
    $report_id = isset($_POST['report_id']) ? intval($_POST['report_id']) : null;
    $status = isset($_POST['status']) ? $_POST['status'] : 'pending'; // Default to 'pending'

    if (!$report_id || !in_array($status, ['pending', 'resolved', 'declined'])) {
        echo "Invalid report ID or status.";
        return;
    }

    try {
        // Update the report status
        $result = $report->updateReportStatus($report_id, $status);

        if ($result) {
            // If the status is resolved, reward points to the volunteer
            if ($status == 'resolved') {
                $report->rewardVolunteerPoints($report_id); // Reward points to the volunteer
            }

            header('location: ../index.php?page=reports');
            exit();
        } else {
            // Handle failure to update status
            echo "Error: Unable to update report status.";
        }
    } catch (Exception $e) {
        // Handle any exceptions
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
