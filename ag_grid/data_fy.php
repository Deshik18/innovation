<?php
include 'config.php';
// Query to retrieve data from a table (replace 'your_table' with your table name)
$sql = "SELECT * FROM gb_fy";

$result = $conn->query($sql);

// Fetch valid "fy" values into an array
$validFyValues = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $validFyValues[] = $row['fy'];
    }
}


// Send valid "fy" values as JSON to the client
echo json_encode($validFyValues);
?>
