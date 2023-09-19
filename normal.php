<!DOCTYPE html>
<html>
<head>
    <title>Your Page Title</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'nav.php';?>
    <div class="content69">
    <?php
    // Include your database connection code (e.g., config.php)
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
            }else{
                $departmentActivityTable[$deptName][$activity]=1;
            }
        }
    }

    echo '<style>
        table {
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: lightgreen;
            color: black;
        }
        tr:nth-child(odd) {
            background-color: lightyellow;
        }
    </style>';

    echo '<table border="1">';
    echo '<tr><th colspan="1">Department-wise-Activity Mapping</th>';
    $columnTotals = [];
    foreach ($resultActivity as $rowActivity) {
        $activityName = $rowActivity['activity'];
        $columnTotals[$activityName] = 0;
    }
    
    // Calculate column totals
    foreach ($departmentActivityTable as $deptActivities) {
        foreach ($deptActivities as $activityName => $count) {
            $columnTotals[$activityName] += $count;
        }
    }
    
    echo '<table border="1">';
    echo '<tr><th colspan="1">Department</th>';
    foreach ($resultActivity as $rowActivity) {
        echo '<th>' . $rowActivity['activity'] . '</th>';
    }
    echo '</tr>';
    
    $rowCount = 0; // Initialize a variable to keep track of row count
    
    foreach ($departmentActivityTable as $deptName => $activities) {
        $rowCount++; // Increment row count for each row
        echo '<tr>';
        echo '<td>' . $deptName . '</td>';
        foreach ($resultActivity as $rowActivity) {
            $activityName = $rowActivity['activity'];
            $count = isset($activities[$activityName]) ? $activities[$activityName] : 0;
            echo '<td>' . $count . '</td>';
        }
        echo '</tr>';
    }
    
    // Add the extra row for column totals
    echo '<tr><td>Total Identified Activity</td>';
    foreach ($columnTotals as $total) {
        echo '<td>' . $total . '</td>';
    }
    echo '</tr>';
    
    echo '</table>';
    echo '<style>';
    echo 'tr:last-child { border: 2px solid crimson; }';
    echo 'tr:last-child { background-color: aqua }'; // Change '#555' to the desired darker border color
    echo '</style>';
    ?>
</div>
</body>
<head>
    <meta charset="UTF-8">
    <title>Stacked Column Chart with Header</title>
    <!-- Include Chart.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
    <h2>Stacked Column Chart</h2>
    <div style="width: 80%; margin: auto;">
        <canvas id="stackedColumnChart" width="400" height="200"></canvas>
    </div>

    <script>
        // JavaScript code to fetch data from the PHP script and create a stacked column chart
        fetch('path_to_script.php')
            .then(response => response.json())
            .then(data => {
                const departments = Object.keys(data);
                const activities = Object.keys(data[departments[0]]);
                
                const datasets = activities.map(activity => ({
                    label: activity,
                    backgroundColor: getRandomColor(), // Helper function to generate random colors
                    data: departments.map(dept => data[dept][activity] || 0),
                }));

                const ctx = document.getElementById('stackedColumnChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: departments,
                        datasets: datasets,
                    },
                    options: {
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true,
                            },
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    title: (context) => {
                                        // Display department as title in tooltip
                                        return data[departments[context[0].dataIndex]].dept;
                                    },
                                    label: (context) => {
                                        // Display activity and value in tooltip
                                        const activity = context.dataset.label;
                                        const value = context.parsed.y;
                                        return `${activity}: ${value}`;
                                    },
                                },
                            },
                        },
                    },
                });
            })
            .catch(error => console.error(error));

        function getRandomColor() {
            // Helper function to generate a random color
            return '#' + Math.floor(Math.random()*16777215).toString(16);
        }
    </script>
</body>
</html>
