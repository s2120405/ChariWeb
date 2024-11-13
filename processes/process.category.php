<?php
// Include Event Class and QR code library
include '../class/categoryclass.php';
include '../phpqrcode/qrlib.php'; // Assuming you're using a QR code library like phpqrcode

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'newcat':
        create_new_category();
        break;
}

function create_new_category() {
    $categories = new Category(); // Assume you have a Category class similar to Event class

    // Retrieve and format data from POST request
    $category_name = trim($_POST['category_name']);
    $category_desc = trim($_POST['category_desc']); // Category description is optional

    try {
        // Call the new_category function to insert the new category
        $result = $categories->new_category($category_name, $category_desc);

        if ($result) {
            // Redirect to the categories page or a success page
            header('location: ../index.php?page=categories');
            exit();
        } else {
            // Handle failure to create category
            echo "Error: Unable to create category.";
        }
    } catch (Exception $e) {
        // Handle any exceptions thrown by new_category
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
