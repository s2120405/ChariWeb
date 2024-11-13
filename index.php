<?php
session_start(); // Start session to access login state

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the page parameter from the URL, defaulting to 'dashboard'
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chariseek</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header id="header">
        <div id="nav" role="navigation">
            <div class="logo">BENRO Chariseek</div>
            <ul id="menu">
                <li><a href="?page=dashboard" data-text="DASHBOARD"><img src="icons/dashboard.png" alt="Dashboard Icon"></a></li>
                <li><a href="?page=events" data-text="EVENTS"><img src="icons/events.png" alt="Events Icon"></a></li>
                <li><a href="?page=calendar" data-text="CALENDAR"><img src="icons/calendar.png" alt="Calendar Icon"></a></li>

                <?php if ($_SESSION['role'] === 'admin') : ?>
                    <!-- Admin-only menu items -->
                    <li><a href="?page=category" data-text="CATEGORY"><img src="icons/category.png" alt="Category Icon"></a></li>
                    <li><a href="?page=voucher" data-text="VOUCHERS"><img src="icons/voucher.png" alt="Voucher Icon"></a></li>
                    <li><a href="?page=reports" data-text="REPORTS"><img src="icons/calendar.png" alt="Reports Icon"></a></li>
                <?php endif; ?>

                <li><a href="logout.php" data-text="LOGOUT">Logout</a></li>
            </ul>
        </div>
    </header>
    
    <div class="content" id="content">
        <?php
        // Load the appropriate content based on the page parameter
        switch ($page) {
            case 'category':
                if ($_SESSION['role'] === 'admin') include 'navbar/category.php';
                break;
            case 'events':
                include 'navbar/events.php';
                break;
            case 'voucher':
                if ($_SESSION['role'] === 'admin') include 'navbar/voucher.php';
                break;
            case 'calendar':
                include 'navbar/calendar.php';
                break;
            case 'reports':
                if ($_SESSION['role'] === 'admin') include 'navbar/reports.php';
                break;
            default:
                include 'navbar/dashboard.php';
        }
        ?>
    </div>

    <script src="javascript.js" defer></script>
    <script>
        // Set the active class based on the current page
        const currentPage = new URLSearchParams(window.location.search).get('page') || 'dashboard';
        const menuItems = document.querySelectorAll('#menu a');
        
        menuItems.forEach(item => {
            if (item.getAttribute('href') === `?page=${currentPage}`) {
                item.classList.add('active');
            }
        });
    </script>
</body>
</html>

