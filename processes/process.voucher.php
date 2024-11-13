<?php
include '../class/voucherclass.php'; // Include the Voucher class

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'createvouch':
        process_voucher_creation();
        break;
}

function process_voucher_creation() {
    // Retrieve form data and sanitize input
    $voucher_title = isset($_POST['voucher_title']) ? $_POST['voucher_title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $points_required = isset($_POST['points_required']) ? intval($_POST['points_required']) : 0;
    $expiration_date = isset($_POST['expiration_date']) ? $_POST['expiration_date'] : '';

    // Validate input
    if (empty($voucher_title) || empty($description) || $points_required <= 0 || empty($expiration_date)) {
        echo "Please fill in all fields with valid data.";
        return;
    }

    // Instantiate Voucher class
    $voucher = new Voucher();

    // Call the createVoucher method from the Voucher class to insert the new voucher
    $created = $voucher->createVoucher($voucher_title, $description, $points_required, $expiration_date);

    // Check if the voucher was created successfully
    if ($created) {
        echo "Voucher created successfully!";
    } else {
        echo "Error: Voucher creation failed.";
    }
}
?>