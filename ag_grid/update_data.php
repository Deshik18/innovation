<?php
include 'config.php'; // Include your database connection configuration

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rowId = $_POST['rowId']; // Replace with the appropriate identifier for your table
    $field = $_POST['field'];
    $newValue = $_POST['newValue'];

    // Update the database with the new value
    $sql = "UPDATE gb_data SET $field = ? WHERE sno = ?"; // Replace 'id' with your table's primary key column
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('si', $newValue, $rowId); // Assuming 'i' is for integer and 's' is for string; adjust as needed
        $stmt->execute();
        $stmt->close();
        
        // Return a success message or updated data if needed
        $response = [
            'status' => 'success',
            'message' => 'Data updated successfully',
            'data' => $newValue, // You can send back the updated data if required
        ];
        echo json_encode($response);
    } else {
        // Handle the database error
        $response = [
            'status' => 'error',
            'message' => 'Error updating data',
        ];
        echo json_encode($response);
    }
} else {
    // Handle other request methods (e.g., GET)
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}
?>
