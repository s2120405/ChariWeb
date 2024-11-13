<!-- orgregister.php -->

<?php
// Include the Organization class
require_once 'class/orgclass.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $org = new Organization();

    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Register the organization
        $registrationSuccess = $org->registerOrganization($name, $email, $password);
        if ($registrationSuccess) {
            header("Location: orglogin.php"); // Redirect to login page on success
            exit();
        } else {
            $error = "Error registering organization. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Register as an Organization</h1>

    <!-- Display error message if exists -->
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="orgregister.php">
        <label for="name">Organization Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="orglogin.php">Log in here</a>.</p>
</body>
</html>
