<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in (authenticated)
if (isset($_SESSION['user_id'])) {
    // Unset all of the session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or main page after logout
    header('Location: ../index.php'); // Change 'login.php' to the appropriate login page URL
    exit();
} else {
    // If the user is not logged in, simply redirect to the main page
    header('Location: ../index.php'); // Change 'main.php' to the appropriate main page URL
    exit();
}
?>
