<?php
include 'config.php';
$sql6 = "SELECT is_on FROM server"; // Assuming you have only one row in the table
$result6 = $conn->query($sql6);

if ($result6->num_rows > 0) {
    $row = $result6->fetch_assoc();
    $isOn = (bool) $row["is_on"];

    if (!$isOn) {
        // Server is in maintenance mode
        echo '<script>alert("Server is currently in maintenance. Please try again later.");</script>';
        echo '<script>window.location.href = "index.php";</script>';
        exit;
    }
} else {
    // Server state not available
    echo '<script>alert("Server state not available.");</script>';
    echo '<script>window.location.href = "index.php";</script>';
    exit;
}


// Check if chartId is provided in the query string
if (isset($_GET['chartId'])) {
    $chartId = $_GET['chartId'];

    // Define a mapping of chart IDs to their corresponding data arrays
    $chartDataMap = [
        'abChart' => ['ab' => 'AB'],
        'agbChart' => ['agb' => 'AGB'],
        'abAgbChart' => ['ab' => 'AG', 'agb' => 'AGB'],
        'rbChart' => ['rb' => 'RB'],
        'rgbChart' => ['rgb' => 'RGB'],
        'rbRgbChart' => ['rb' => 'RG', 'rgb' => 'RGB'],
        'beChart' => ['be' => 'BE'],
        'gbeChart' => ['gbe' => 'GBE'],
        'beGbeChart' => ['be' => 'BE', 'gbe' => 'GBE'],
        // Add mappings for other charts as needed
    ];

    // Check if the provided chartId is valid and exists in the mapping
    if (array_key_exists($chartId, $chartDataMap)) {
        $selectedDataFields = $chartDataMap[$chartId];

        $query = "SELECT fy";
        foreach ($selectedDataFields as $field => $label) {
            $query .= ", SUM($field) AS $label";
        }
        $query .= " FROM gb_data GROUP BY fy";
        $result = $conn->query($query);

        // Fetch data and store it in the $data array
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Generate CSV data
        $csvData = 'FY,' . implode(',', array_values($selectedDataFields)) . "\n";
        foreach ($data as $row) {
            $fy = $row['fy'];
            $rowData = [];
            foreach ($selectedDataFields as $field => $label) {
                $rowData[] = $row[$label];
            }
            $csvData .= "$fy," . implode(',', $rowData) . "\n";
        }

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="chart_data.csv"');


        // Output the CSV data
        echo $csvData;
        exit; // Terminate the script after outputting the CSV to prevent further output
    } else {
        echo 'Invalid chart identifier';
    }
} else {
    echo 'Chart identifier not provided';
}
?>
