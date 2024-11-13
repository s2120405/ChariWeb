<?php

session_start();
// Include Event Class and QR code library
include '../class/eventclass.php';
include '../phpqrcode/qrlib.php'; // Assuming you're using a QR code library like phpqrcode

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'new':
        create_new_event();
        break;
    case 'editevent':
        edit_event();
        break;
}

// Function that creates a new event
function create_new_event() {
    $events = new Event();
    
    // Retrieve and format data from POST request
    $title = ucwords(trim($_POST['title']));
    $description = trim($_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $location = ucwords(trim($_POST['location']));
    $max_volunteers = $_POST['max_volunteers'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $points = isset($_POST['points']) ? $_POST['points'] : 0; // Default to 0 if not set

    $event_status = 'upcoming'; // Default event status

    // Determine created_by_admin based on user role in session
    $created_by_admin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 1 : 0;

    // Retrieve categories as an array
    $categories = isset($_POST['categories']) ? $_POST['categories'] : []; // Handle multiple category checkboxes

    // Handle image upload
    $image_destination = null; // Initialize image destination to null
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['event_image']['name']);
        $image_tmp = $_FILES['event_image']['tmp_name'];
        $image_destination = '../uploads/' . $image_name;

        // Move the uploaded file and check for success
        if (!move_uploaded_file($image_tmp, $image_destination)) {
            // If moving failed, handle the error (optional logging)
            error_log("Failed to move uploaded file to: $image_destination");
            $image_destination = null; // Reset to null if move fails
        }
    }

    // Get organization_id if the user is an organization
    $organization_id = isset($_SESSION['role']) && $_SESSION['role'] === 'organization' ? $_SESSION['organization_id'] : null;

    // Call the new_event function to insert the event with all details
    try {
        $result = $events->new_event(
            $title, $description, $start_date, $end_date, $start_time, $end_time, 
            $latitude, $longitude, $location, $max_volunteers, $image_destination, 
            $points, $event_status, $created_by_admin, $categories, $organization_id // Pass organization_id
        );

        if ($result) {
            header('location: ../index.php?page=events');
            exit();
        } else {
            // Handle failure to create event
            echo "Error: Unable to create event.";
        }
    } catch (Exception $e) {
        // Handle any exceptions thrown by new_event
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}

function edit_event() {
    $events = new Event();

    // Retrieve and format data from POST request
    $event_id = $_POST['event_id']; // Get event ID from POST
    $title = ucwords(trim($_POST['title']));
    $description = trim($_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $location = ucwords(trim($_POST['location']));
    $max_volunteers = $_POST['max_volunteers'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $points = isset($_POST['points']) ? $_POST['points'] : 0; // Default to 0 if not set
    $event_status = isset($_POST['event_status']) ? $_POST['event_status'] : 'upcoming';
    $created_by_admin = isset($_POST['created_by_admin']) ? $_POST['created_by_admin'] : false;

    // Retrieve categories as an array
    $categories = isset($_POST['categories']) ? $_POST['categories'] : []; // Handle multiple category checkboxes

    // Handle image upload
    $image_destination = null; // Initialize image destination to null
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['event_image']['name']);
        $image_tmp = $_FILES['event_image']['tmp_name'];
        $image_destination = '../uploads/' . $image_name;

        // Move the uploaded file and check for success
        if (!move_uploaded_file($image_tmp, $image_destination)) {
            // If moving failed, handle the error (optional logging)
            error_log("Failed to move uploaded file to: $image_destination");
            $image_destination = null; // Reset to null if move fails
        }
    }

    // Call the update_event function to edit the event with all new details
    try {
        $result = $events->update_event(
            $event_id, $title, $description, $start_date, $end_date, $start_time, 
            $end_time, $latitude, $longitude, $location, $max_volunteers, $image_destination, 
            $points, $event_status, $created_by_admin, $categories // Pass categories array
        );

        if ($result) {
            header('location: ../index.php?page=dashboard'); // Redirect to events page if successful
            exit();
        } else {
            // Handle failure to update event
            echo "Error: Unable to update event.";
        }
    } catch (Exception $e) {
        // Handle any exceptions thrown by update_event
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}

