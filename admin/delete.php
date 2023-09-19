<?php
session_start();

// Check if the user is not authenticated (e.g., not logged in)
if (!isset($_SESSION['user_id'])) {
    // Display a JavaScript alert message
    echo '<script>alert("You can\'t access this page. Please log in.");</script>';
    
    // Add a delay before redirecting (e.g., 3 seconds)
    echo '<meta http-equiv="refresh" content="3;url=../index.php">';

    // Exit to prevent further PHP execution
    exit();
}
include 'config.php'; // Include your database connection

if (isset($_GET['rowId'])) {
    $rowId = $_GET['rowId'];

    // Prepare and execute the SQL DELETE statement
    $sql = "DELETE FROM gb_data WHERE sno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rowId);

    if ($stmt->execute()) {
        echo "Row deleted successfully.";
    } else {
        echo "Error deleting the row: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
