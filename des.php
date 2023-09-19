<!DOCTYPE html>
<html>
<head>
    <title>Highest Metrics by Department</title>
    <!-- Include Chart.js library -->
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <style>
        /* Add some CSS styles for the table */
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        /* Apply light yellow background to even rows */
        tr:nth-child(even) {
            background-color: #ffffcc;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?> <!-- Include the navigation bar -->

<!-- Existing content container with a unique class name -->
<div class="content69">
    <h1>Highest Metrics by Department</h1>

    <?php
    include 'config.php';

    $availableYears = [];

    $sqlFy = "SELECT fy FROM gb_fy";
    $resultFy = $conn->query($sqlFy);

    if ($resultFy->num_rows > 0) {
        while ($row = $resultFy->fetch_assoc()) {
            $availableYears[] = $row["fy"];
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the selected financial year from the form
        $selectedYear = $_POST["financial_year"];

        // Execute separate SQL queries to retrieve highest gb and rgb values by department for the selected year
        $sqlAgb = "SELECT dept, SUM(agb) as agb FROM gb_data WHERE fy = '$selectedYear' GROUP BY dept ORDER BY agb DESC";
        $resultAgb = $conn->query($sqlAgb);

        $sqlRgb = "SELECT dept, SUM(rgb) as rgb FROM gb_data WHERE fy = '$selectedYear' GROUP BY dept ORDER BY rgb DESC";
        $resultRgb = $conn->query($sqlRgb);

        $sqlGbe = "SELECT dept, SUM(gbe) as gbe FROM gb_data WHERE fy = '$selectedYear' GROUP BY dept ORDER BY gbe DESC";
        $resultGbe = $conn->query($sqlGbe);

        $tableDatAgb = [];
        $tableDataRgb = [];
        $tableDataGbe = [];
        $chartLabels = [];
        $chartDatAgb = [];
        $chartDataRgb = [];
        $chartDataGbe = [];
        $randomColors = [];

        if ($resultAgb->num_rows > 0) {
            while ($rowAgb = $resultAgb->fetch_assoc()) {
                // Populate data for the gb table
                $dept = $rowAgb["dept"];
                $agb = $rowAgb["agb"];
                $tableDataAgb[] = ["dept" => $dept, "agb" => $agb];

                // Populate data for the gb pie chart
                $chartLabels[] = $dept;
                $chartDataAgb[] = $agb;

                // Generate random color for the department
                $randomColor = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
                $randomColors[] = $randomColor;
            }
            // Display the results for gb in a table
            echo "<h2>Top AGB Metrics for Financial Year: $selectedYear</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Department</th><th>Top AGB</th></tr>";

            foreach ($tableDataAgb as $index => $rowAgb) {
                echo "<tr>";
                echo "<td>" . $rowAgb["dept"] . "</td>";
                echo "<td>" . $rowAgb["agb"] . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            // Generate a gb pie chart with reduced size (20%)
            echo "<h2>AGB Pie Chart: Department Distribution</h2>";
            echo "<canvas id='pieChartGb' style='width: 60%; height: 200px;'></canvas>";

            // JavaScript to create the gb pie chart
            echo "<script>";
            echo "var ctxGb = document.getElementById('pieChartGb').getContext('2d');";
            echo "var dataGb = {";
            echo "labels: " . json_encode($chartLabels) . ",";
            echo "datasets: [{";
            echo "data: " . json_encode($chartDataAgb) . ",";
            echo "backgroundColor: " . json_encode($randomColors) . ",";
            echo "}]";
            echo "};";
            echo "var pieChartGb = new Chart(ctxGb, {";
            echo "type: 'pie',";
            echo "data: dataGb";
            echo "});";
            echo "</script>";
        }else {
            // No AGB data found for this financial year
            echo "<h2>No Department Budget in AGB Found for Financial Year: $selectedYear</h2>";
        }
        if ($resultRgb->num_rows > 0) {
            while ($rowRgb = $resultRgb->fetch_assoc()) {
                // Populate data for the rgb table
                $dept = $rowRgb["dept"];
                $rgb = $rowRgb["rgb"];
                $tableDataRgb[] = ["dept" => $dept, "rgb" => $rgb];

                // Populate data for the rgb pie chart
                $chartDataRgb[] = $rgb;
            }
            // Display the results for rgb in a table
            echo "<h2>Top RGB Metrics for Financial Year: $selectedYear</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Department</th><th>Top RGB</th></tr>";

            foreach ($tableDataRgb as $index => $rowRgb) {
                echo "<tr>";
                echo "<td>" . $rowRgb["dept"] . "</td>";
                echo "<td>" . $rowRgb["rgb"] . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            // Generate an rgb pie chart with reduced
            echo "<h2>RGB Pie Chart: Department Distribution</h2>";
            echo "<canvas id='pieChartRgb' style='width: 60%; height: 200px;'></canvas>";

            // JavaScript to create the rgb pie chart
            echo "<script>";
            echo "var ctxRgb = document.getElementById('pieChartRgb').getContext('2d');";
            echo "var dataRgb = {";
            echo "labels: " . json_encode($chartLabels) . ",";
            echo "datasets: [{";
            echo "data: " . json_encode($chartDataRgb) . ",";
            echo "backgroundColor: " . json_encode($randomColors) . ",";
            echo "}]";
            echo "};";
            echo "var pieChartRgb = new Chart(ctxRgb, {";
            echo "type: 'pie',";
            echo "data: dataRgb";
            echo "});";
            echo "</script>";
        }else {
            // No RGB data found for this financial year
            echo "<h2>No Department Budget in RGB Found for Financial Year: $selectedYear</h2>";
        }

        if ($resultGbe->num_rows > 0) {
            while ($rowGbe = $resultGbe->fetch_assoc()) {
                // Populate data for the rgb table
                $dept = $rowGbe["dept"];
                $gbe = $rowGbe["gbe"];
                $tableDataGbe[] = ["dept" => $dept, "gbe" => $gbe];

                // Populate data for the rgb pie chart
                $chartDataGbe[] = $gbe;
            }
            echo "<h2>Top GBE Metrics for Financial Year: $selectedYear</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Department</th><th>Top GBE</th></tr>";

            foreach ($tableDataGbe as $index => $rowGbe) {
                echo "<tr>";
                echo "<td>" . $rowGbe["dept"] . "</td>";
                echo "<td>" . $rowGbe["gbe"] . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            // Generate an rgb pie chart with reduced
            echo "<h2>GBE Pie Chart: Department Distribution</h2>";
            echo "<canvas id='pieChartGbe' style='width: 60%; height: 200px;'></canvas>";

            // JavaScript to create the rgb pie chart
            echo "<script>";
            echo "var ctxGbe = document.getElementById('pieChartGbe').getContext('2d');";
            echo "var dataGbe = {";
            echo "labels: " . json_encode($chartLabels) . ",";
            echo "datasets: [{";
            echo "data: " . json_encode($chartDataGbe) . ",";
            echo "backgroundColor: " . json_encode($randomColors) . ",";
            echo "}]";
            echo "};";
            echo "var pieChartGbe = new Chart(ctxGbe, {";
            echo "type: 'pie',";
            echo "data: dataGbe";
            echo "});";
            echo "</script>";
        } else {
            // No GBE data found for this financial year
            echo "<h2>No Department Budget in GBE Found for Financial Year: $selectedYear</h2>";
        }
    } else {
        // Display the form to select the financial year
        echo "<style>";
        echo "<style>";
        echo ".button-container {";
        echo "    text-align: center; /* Center-align the buttons */";
        echo "}";
        echo ".button-container p {";
        echo "    font-size: 24px; /* Increase the font size for the text */";
        echo "}";
        echo ".rounded-button {";
        echo "    width: 100px; /* Adjust the width as needed */";
        echo "    height: 100px; /* Set the same value as the width to make it round */";
        echo "    margin: 5px; /* Add some spacing between buttons */";
        echo "    background-color: #007BFF; /* Set the button background color */";
        echo "    color: #fff; /* Set the text color to white */";
        echo "    border: none; /* Remove the default button border */";
        echo "    border-radius: 50%; /* Make the button round */";
        echo "    font-size: 16px; /* Adjust the font size */";
        echo "    cursor: pointer; /* Add a pointer cursor on hover */";
        echo "    transition: background-color 0.3s; /* Add a smooth transition effect */";
        echo "}";
        echo ".rounded-button:hover {";
        echo "    background-color: #0056b3; /* Change the background color on hover */";
        echo "}";
        echo "</style>";
        
        echo "<form method='post'>";
        echo "<div class='button-container'>";
        echo "<p>Please Select Financial Year</p>";
        foreach ($availableYears as $index => $year) {
            // Create a rounded button with an icon for each available financial year
            echo "<button class='rounded-button' type='submit' name='financial_year' value='$year'>$year</button>";
        
            // Add a line break after every 5 buttons
            if (($index + 1) % 5 == 0) {
                echo "<br>";
            }
        }
        echo "</div>";
        echo "</form>";
        
    }
    ?>
</div>
</body>
</html>
