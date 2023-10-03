<?php
include 'config.php';
// Retrieve the is_on value from the server table
$sql = "SELECT is_on FROM server LIMIT 1"; // Assuming you have only one row in the table
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $isOn = (bool) $row["is_on"];

    if ($isOn) {
        // Server is not in maintenance mode
        echo '1'; // Return '1' to indicate not in maintenance mode
    } else {
        // Server is in maintenance mode
        echo '0'; // Return '0' to indicate maintenance mode
    }
} else {
    // Server state not available
    echo '0'; // Return '0' to indicate maintenance mode (as a fallback)
}

// Close the database connection
$conn->close();
?>
