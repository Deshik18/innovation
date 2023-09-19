<?php
include 'config.php';
// Query to retrieve data from a table (replace 'your_table' with your table name)
$sql = "SELECT * FROM gb_data";
$result = $conn->query($sql);

// Fetch data as an associative array
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>
