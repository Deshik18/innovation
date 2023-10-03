<?php
include 'config.php';

// Fetch financial years
$sql = "SELECT DISTINCT fy FROM gb_fy";
$result = $conn->query($sql);

$financialYears = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $financialYears[] = $row['fy'];
    }
}
$departmentData = [];
$departments = [];
$abData = [];
$agbData = [];
$rbData = [];
$rgbData = [];
$beData = [];
$gbeData =  [];

// Check if a financial year is selected
if (isset($_POST['selected_fy'])) {
    $selectedFY = $_POST['selected_fy'];

    // Check if "Select All" is selected
    if (in_array("Select All", $selectedFY)) {
        $selectedFY = true;
    }

    // Fetch department data
    if ($selectedFY === true) {
        $sql = "SELECT dept, SUM(ab) as ab, SUM(rb) as rb, SUM(rgb) as rgb, SUM(be) as be, SUM(gbe) as gbe, SUM(agb) as agb FROM gb_data GROUP BY dept";
    } else {
        $selectedFY = "'" . implode("','", $selectedFY) . "'";
        $sql = "SELECT dept, SUM(ab) as ab, SUM(rb) as rb, SUM(rgb) as rgb, SUM(be) as be, SUM(gbe) as gbe, SUM(agb) as agb FROM gb_data WHERE fy IN ($selectedFY) GROUP BY dept";
    }

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $departmentData[] = $row;
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Financial Year Selection</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Include Chart.js library here -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
        }

        .container {
            max-width: 1800px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-container {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
        }

        .form-container input[type="checkbox"] {
            margin-right: 5px;
        }
        .download-button {
            background-color: goldenrod; /* White background */
            color: #007bff; /* Text color */
            border: none;
            border-radius: 50%; /* Make it a circle */
            width: 30px; /* Adjust the width for the circle */
            height: 30px; /* Adjust the height for the circle */
            padding: 0; /* Remove padding */
            cursor: pointer;
            text-decoration: none; /* Remove underlines */
            display: flex; /* Align icon and text horizontally */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            position: absolute;
            top: 5px; /* Adjust top position */
            right: 5px; /* Adjust right position */
        }

        /* Hover effect for the download button */
        .download-button:hover {
            background-color: #f0f0f0; /* Change color on hover */
        }

        /* Style for the Font Awesome download icon */
        .fa-download {
            margin: 0; /* Remove margin */
        }

        .chart-container {
            width: 80%;
        }

        /* Increase the font size of chart labels */
        .chart-label {
            font-size: 16px;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?> <!-- Include the navigation bar -->

<!-- Existing content container with a unique class name -->
<div class="content69">
    <h1>Select Financial Year</h1>
    <?php if (empty($selectedFY)) { ?>
        <form method="post" action="">
            <!-- Add "Select All" checkbox -->
            <label><input type="checkbox" name="selected_fy[]" value="Select All"> Select All</label><br>
            <?php
            foreach ($financialYears as $fy) {
                echo '<label><input type="checkbox" name="selected_fy[]" value="' . $fy . '">' . $fy . '</label><br>';
            }
            ?>
            <input type="submit" value="Apply">
        </form>
    <?php } ?>
    
    <?php if (!empty($selectedFY)) { ?>
        <div>
            <h2>Bar Graphs for Selected FY: 
            <?php
            if ($selectedFY === true) {
                echo 'All';
            } else {
                echo $selectedFY;
            }
                foreach ($departmentData as $dept) {
                $departments[] = $dept['dept'];
                $abData[] = $dept['ab'];
                $agbData[] = $dept['agb'];
                $rbData[] = $dept['rb'];
                $rgbData[] = $dept['rgb'];
                $beData[] = $dept['be'];
                $gbeData[]= $dept['gbe'];
            }
            ?>
            </h2>

            <!-- Create a container for the charts -->
            <div class="chart-container" style="display: flex; flex-wrap: wrap; gap: 20px;">
                <?php
                $charts = [
                    ['id' => 'abChart', 'label' => 'AB Chart'],
                    ['id' => 'agbChart', 'label' => 'AGB Chart'],
                    ['id' => 'multiSeries1Chart', 'label' => 'Multi-Series 1 Chart'],
                    ['id' => 'rbChart', 'label' => 'RB Chart'],
                    ['id' => 'rgbChart', 'label' => 'RGB Chart'],
                    ['id' => 'multiSeries2Chart', 'label' => 'Multi-Series 2 Chart'],
                    ['id' => 'beChart', 'label' => 'BE Chart'],
                    ['id' => 'gbeChart', 'label' => 'GBE Chart'],
                    ['id' => 'multiSeries3Chart', 'label' => 'Multi-Series 3 Chart'],
                ];

                $chartCount = count($charts);

                for ($i = 0; $i < $chartCount; $i++) {
                    echo '<div class="chart-container" style="border: 1px solid #ccc; padding: 10px; position: relative; box-sizing: border-box;">';
                    echo '<h3 class="chart-label">' . $charts[$i]['label'] . '</h3>';
                    echo '<canvas id="' . $charts[$i]['id'] . '"></canvas>';
                    
                    // Use an <a> tag with styling for the download button
                    echo '<a class="download-button" href="#" id="downloadCSV' . ($i + 1) . '" data-filename="chart' . ($i + 1) . '-data.csv" data-labels=' . json_encode($departments) . ' data-chart-id=' . json_encode($charts[$i]['id']) . '>';
                    echo '<i class="fas fa-download"></i>';
                    echo '</a>';

                    echo '</div>';
                }
                ?>
            </div>
        </div>
    <?php } ?>
</div>
    <script>
        // Create Bar Chart for AB
        var abChart = new Chart(document.getElementById('abChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($departments) ?>,
                datasets: [{
                    label: 'AB',
                    data: <?= json_encode($abData) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }]
            }
        });

        // Create Bar Chart for AGB
        var agbChart = new Chart(document.getElementById('agbChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($departments) ?>,
                datasets: [{
                    label: 'AGB',
                    data: <?= json_encode($agbData) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }]
            }
        });

        // Create Multi-Series Chart 1
        var multiSeries1Data = {
            labels: <?= json_encode($departments) ?>,
            datasets: [
                {
                    label: 'AB',
                    data: <?= json_encode($abData) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                },
                {
                    label: 'AGB',
                    data: <?= json_encode($agbData) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }
            ]
        };

        var multiSeries1Chart = new Chart(document.getElementById('multiSeries1Chart'), {
            type: 'bar',
            data: multiSeries1Data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Create Bar Chart for RB
        var rbChart = new Chart(document.getElementById('rbChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($departments) ?>,
                datasets: [{
                    label: 'RB',
                    data: <?= json_encode($rbData) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }]
            }
        });

        // Create Bar Chart for RGB
        var rgbChart = new Chart(document.getElementById('rgbChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($departments) ?>,
                datasets: [{
                    label: 'RGB',
                    data: <?= json_encode($rgbData) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }]
            }
        });

        // Create Multi-Series Chart 2
        var multiSeries2Data = {
            labels: <?= json_encode($departments) ?>,
            datasets: [
                {
                    label: 'RB',
                    data: <?= json_encode($rbData) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                },
                {
                    label: 'RGB',
                    data: <?= json_encode($rgbData) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }
            ]
        };

        var multiSeries2Chart = new Chart(document.getElementById('multiSeries2Chart'), {
            type: 'bar',
            data: multiSeries2Data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Create Bar Chart for BE
        var beChart = new Chart(document.getElementById('beChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($departments) ?>,
                datasets: [{
                    label: 'BE',
                    data: <?= json_encode($beData) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }]
            }
        });

        // Create Bar Chart for GBE
        var gbeChart = new Chart(document.getElementById('gbeChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($departments) ?>,
                datasets: [{
                    label: 'GBE',
                    data: <?= json_encode($gbeData) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }]
            }
        });

        // Create Multi-Series Chart 3
        var multiSeries3Data = {
            labels: <?= json_encode($departments) ?>,
            datasets: [
                {
                    label: 'BE',
                    data: <?= json_encode($beData) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                },
                {
                    label: 'GBE',
                    data: <?= json_encode($gbeData) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                }
            ]
        };

        var multiSeries3Chart = new Chart(document.getElementById('multiSeries3Chart'), {
            type: 'bar',
            data: multiSeries3Data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Add click event listeners to download buttons
        <?php
        for ($i = 0; $i < $chartCount; $i++) {
            echo 'document.getElementById("downloadCSV' . ($i + 1) . '").addEventListener("click", function () {';
            echo 'downloadCSV("chart' . ($i + 1) . '-data.csv", ' . json_encode($departments) . ', ' . json_encode($charts[$i]['id']) . ');';
            echo '});';
        }
        ?>

        // Function to download CSV
        function downloadCSV(filename, labels, chartId) {
            // Check server maintenance mode via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'check_maintenance.php', true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = xhr.responseText;
                    
                    if (response === '1') {
                        // Server is not in maintenance mode, proceed with CSV download
                        var csv = 'Department,' + labels.join(',') + '\n';
                        var chartData = [];
                        var chart = window[chartId];
                        
                        for (var i = 0; i < chart.data.datasets.length; i++) {
                            var data = chart.data.datasets[i].data;
                            chartData.push(data.join(','));
                        }
                        
                        for (var i = 0; i < labels.length; i++) {
                            csv += labels[i] + ',' + chartData.map(row => row.split(',')[i]).join(',') + '\n';
                        }
                        
                        var blob = new Blob([csv], { type: 'text/csv' });
                        if (window.navigator.msSaveOrOpenBlob) {
                            window.navigator.msSaveOrOpenBlob(blob, filename);
                        } else {
                            var a = document.createElement('a');
                            a.href = URL.createObjectURL(blob);
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        }
                    } else {
                        // Server is in maintenance mode, show an alert
                        alert('Server is currently in maintenance. Please try again later.');
                        window.location.href = "index.php";
                    }
                }
            };

            xhr.send();
        }

    </script>
</body>
</html>
