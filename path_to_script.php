<?php
include 'config.php';
// Fetch unique department names from gb_dept
$sqlDept = "SELECT DISTINCT dept_name FROM gb_dept";
$resultDept = $conn->query($sqlDept);

// Fetch unique activity names from gb_activity
$sqlActivity = "SELECT DISTINCT activity FROM gb_activity";
$resultActivity = $conn->query($sqlActivity);

// Initialize a 2-D array to store department vs. activity values
$departmentActivityTable = [];

// Initialize the table with all values set to zero
while ($rowDept = $resultDept->fetch_assoc()) {
    $deptName = $rowDept['dept_name'];
    $departmentActivityTable[$deptName] = array_fill_keys([], 0);
}

// Iterate through the gb_data table
$sqlData = "SELECT dept, activity FROM gb_data";
$resultData = $conn->query($sqlData);

while ($rowData = $resultData->fetch_assoc()) {
    $deptName = $rowData['dept'];
    $activities = explode(',', $rowData['activity']);

    // Increment values in the 2-D array based on department and activities
    foreach ($activities as $activity) {
        $activity = trim($activity);
        if (isset($departmentActivityTable[$deptName][$activity])) {
            $departmentActivityTable[$deptName][$activity]++;
        } else {
            $departmentActivityTable[$deptName][$activity] = 1;
        }
    }
}

// Close the database connection
$conn->close();

// Create an associative array containing all the fetched data
$response = $departmentActivityTable;

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
