<?php
session_start(); // Start the session at the beginning of the script

include 'class/adminclass.php';
include 'class/orgclass.php';

$admin = new Admin();
$organization = new Organization();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['user_type']; // New field to identify user type
    
    if ($userType === 'admin') {
        // Admin login
        if ($admin->login($email, $password)) {
            // Set session variables for admin login
            $_SESSION['loggedin'] = true;
            $_SESSION['role'] = 'admin'; // Store the role in session
            header("Location: index.php"); // Redirect to admin dashboard
            exit();
        } else {
            $error = "Invalid admin email or password!";
        }
    } elseif ($userType === 'organization') {
        // Organization login
        if ($organization->orglogin($email, $password)) {
            // Set session variables for organization login (already set within orglogin)
            header("Location: index.php"); // Redirect to organization dashboard
            exit();
        } else {
            $error = "Invalid organization email or password!";
        }
    } else {
        $error = "Please select a valid user type.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>
        
        <label for="user_type">I am a:</label>
        <select name="user_type" id="user_type" required>
            <option value="">Select User Type</option>
            <option value="admin">Admin</option>
            <option value="organization">Organization</option>
        </select><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
        
        <p>
            <button type="button" onclick="window.location.href='register.php'">Register as Admin</button>
            <button type="button" onclick="window.location.href='orgregister.php'">Register as Organization</button>
        </p>
    </form>
</body>
</html>
