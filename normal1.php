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
    include 'config.php';
    $query = "SELECT fy, SUM(ab) AS ab_sum, SUM(agb) AS agb_sum, SUM(rb) as rb_sum, SUM(rgb) AS rgb_sum, SUM(be) as be_sum, SUM(gbe) as gbe_sum FROM gb_data GROUP BY fy";
    $result = $conn->query($query);

    // Fetch data and store it in the $data array
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    ?>
    </div>
</body>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <title>Multiple Axis Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .graph-container {
            display: flex;
            flex-wrap: wrap;
        }
        .graph-box {
            width: calc(31.2% - 20px); /* Set the desired width for each graph with a little margin */
            margin: 10px;
            border: 1px solid #ccc; /* Add a border to each graph box */
            padding: 10px; /* Add padding to separate the graph and button */
            position: relative; /* Make the container relative for absolute positioning of button */
        }
        .graph-box canvas {
            height: 280px;
        }
        .export-button {
            position: absolute;
            top: 5px; /* Adjust the top position for button */
            right: 5px; /* Adjust the right position for button */
            background-color: lightsteelblue;
            color: darkmagenta;
            border: none;
            border-radius: 50%; /* Make it a circle */
            width: 30px; /* Set the width and height to your desired size */
            height: 30px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
        }

        /* Style for the circle button on hover */
        .export-button:hover {
            background-color: crimson;
        }
    </style>
</head>
<body>
    <div class="graph-container">
        <div class="graph-box">
            <canvas id="abChart" height="200"></canvas>
            <button class="export-button" onclick="exportChart('abChart')"><i class="fas fa-download"></i>
            </button>
        </div>
        <div class="graph-box">
            <canvas id="agbChart" height="200"></canvas>
            <button class="export-button" onclick="exportChart('agbChart')"><i class="fas fa-download"></i>
            </button>
        </div>
        <div class="graph-box">
            <canvas id="abAgbChart" height="200"></canvas>
            <button class="export-button" onclick="exportChart('abAgbChart')"><i class="fas fa-download"></i>
            </button>
        </div>
        <div class="graph-box">
            <canvas id="rbChart" height="200"></canvas>
            <button class="export-button" onclick="exportChart('rbChart')"><i class="fas fa-download"></i>
            </button>
        </div>
        <div class="graph-box">
            <canvas id="rgbChart" height="200"></canvas>
            <button class="export-button" onclick="exportChart('rgbChart')"><i class="fas fa-download"></i>
            </button>
        </div>
        <div class="graph-box">
            <canvas id="rbRgbChart" height="200"></canvas>
            <button class="export-button" onclick="exportChart('rbRgbChart')"><i class="fas fa-download"></i>
            </button>
        </div>
        <div class="graph-box">
            <canvas id="beChart" height="200"></canvas>
            <button class="export-button" onclick="exportChart('beChart')"><i class="fas fa-download"></i>
            </button>
        </div>
        <div class="graph-box">
            <canvas id="gbeChart" height="200"></canvas>
            <button class="export-button" onclick="exportChart('gbeChart')"><i class="fas fa-download"></i>
            </button>
        </div>
        <div class="graph-box">
            <canvas id="beGbeChart" height="200"></canvas>
            <button class="export-button" onclick="exportChart('beGbeChart')"><i class="fas fa-download"></i>
            </button>
        </div>
    </div>
    
    <script>
        // Data retrieved from PHP
        var data = <?php echo json_encode($data); ?>;
        
        // Prepare data for each chart
        var fyLabels = data.map(item => item.fy);
        var abData = data.map(item => item.ab_sum);
        var agbData = data.map(item => item.agb_sum);
        var rbData = data.map(item => item.rb_sum);
        var rgbData = data.map(item => item.rgb_sum);
        var beData = data.map(item => item.be_sum);
        var gbeData = data.map(item => item.gbe_sum);

        // Create AB chart (Bar Graph)
        var abChartCtx = document.getElementById('abChart').getContext('2d');
        var abChart = new Chart(abChartCtx, {
            type: 'bar',
            data: {
                labels: fyLabels,
                datasets: [{
                    label: 'AB vs FY',
                    data: abData,
                    backgroundColor: 'blue',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'AB'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'FY'
                        }
                    }
                }
            }
        });

        // Create AGB chart (Bar Graph)
        var agbChartCtx = document.getElementById('agbChart').getContext('2d');
        var agbChart = new Chart(agbChartCtx, {
            type: 'bar',
            data: {
                labels: fyLabels,
                datasets: [{
                    label: 'AGB vs FY',
                    data: agbData,
                    backgroundColor: 'green',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'AGB'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'FY'
                        }
                    }
                }
            }
        });

        // Create AB + AGB chart (Multiple Axis Graph)
        var abAgbChartCtx = document.getElementById('abAgbChart').getContext('2d');
        var abAgbChart = new Chart(abAgbChartCtx, {
            type: 'bar',
            data: {
                labels: fyLabels,
                datasets: [{
                    label: 'AB',
                    data: fyLabels.map((fy, index) => ({ x: fy, y: abData[index] })),
                    backgroundColor: 'blue',
                    yAxisID: 'y-axis-1',
                }, {
                    label: 'AGB',
                    data: fyLabels.map((fy, index) => ({ x: fy, y: agbData[index] })),
                    backgroundColor: 'green',
                    yAxisID: 'y-axis-2',
                }]
            },
            options: {
                scales: {
                    y: [
                        {
                            id: 'y-axis-1',
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: 'AB'
                            }
                        },
                        {
                            id: 'y-axis-2',
                            type: 'linear',
                            position: 'right',
                            title: {
                                display: true,
                                text: 'AGB'
                            }
                        }
                    ],
                    x: {
                        title: {
                            display: true,
                            text: 'FY'
                        }
                    }
                }
            }
        });

        var rbChartCtx = document.getElementById('rbChart').getContext('2d');
        var rbChart = new Chart(rbChartCtx, {
            type: 'bar',
            data: {
                labels: fyLabels,
                datasets: [{
                    label: 'RB vs FY',
                    data: rbData,
                    backgroundColor: 'gold',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'RB'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'FY'
                        }
                    }
                }
            }
        });

        // Create AGB chart (Bar Graph)
        var rgbChartCtx = document.getElementById('rgbChart').getContext('2d');
        var rgbChart = new Chart(rgbChartCtx, {
            type: 'bar',
            data: {
                labels: fyLabels,
                datasets: [{
                    label: 'RGB vs FY',
                    data: rgbData,
                    backgroundColor: 'silver',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'RGB'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'FY'
                        }
                    }
                }
            }
        });

        // Create AB + AGB chart (Multiple Axis Graph)
        var rbRgbChartCtx = document.getElementById('rbRgbChart').getContext('2d');
        var rbRgbChart = new Chart(rbRgbChartCtx, {
            type: 'bar',
            data: {
                labels: fyLabels,
                datasets: [{
                    label: 'RB',
                    data: fyLabels.map((fy, index) => ({ x: fy, y: rbData[index] })),
                    backgroundColor: 'gold',
                    yAxisID: 'y-axis-1',
                }, {
                    label: 'RGB',
                    data: fyLabels.map((fy, index) => ({ x: fy, y: rgbData[index] })),
                    backgroundColor: 'silver',
                    yAxisID: 'y-axis-2',
                }]
            },
            options: {
                scales: {
                    y: [
                        {
                            id: 'y-axis-1',
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: 'RB'
                            }
                        },
                        {
                            id: 'y-axis-2',
                            type: 'linear',
                            position: 'right',
                            title: {
                                display: true,
                                text: 'RGB'
                            }
                        }
                    ],
                    x: {
                        title: {
                            display: true,
                            text: 'FY'
                        }
                    }
                }
            }
        });

        var beChartCtx = document.getElementById('beChart').getContext('2d');
        var beChart = new Chart(beChartCtx, {
            type: 'bar',
            data: {
                labels: fyLabels,
                datasets: [{
                    label: 'BE vs FY',
                    data: beData,
                    backgroundColor: 'violet',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'BE'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'FY'
                        }
                    }
                }
            }
        });

        // Create AGB chart (Bar Graph)
        var gbeChartCtx = document.getElementById('gbeChart').getContext('2d');
        var gbeChart = new Chart(gbeChartCtx, {
            type: 'bar',
            data: {
                labels: fyLabels,
                datasets: [{
                    label: 'GBE vs FY',
                    data: gbeData,
                    backgroundColor: 'aqua',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'GBE'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'FY'
                        }
                    }
                }
            }
        });

        var beGbeChartCtx = document.getElementById('beGbeChart').getContext('2d');
        var beGbeChart = new Chart(beGbeChartCtx, {
            type: 'bar',
            data: {
                labels: fyLabels,
                datasets: [{
                    label: 'GB',
                    data: fyLabels.map((fy, index) => ({ x: fy, y: beData[index] })),
                    backgroundColor: 'violet',
                    yAxisID: 'y-axis-1',
                }, {
                    label: 'GBE',
                    data: fyLabels.map((fy, index) => ({ x: fy, y: gbeData[index] })),
                    backgroundColor: 'aqua',
                    yAxisID: 'y-axis-2',
                }]
            },
            options: {
                scales: {
                    y: [
                        {
                            id: 'y-axis-1',
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: 'BE'
                            }
                        },
                        {
                            id: 'y-axis-2',
                            type: 'linear',
                            position: 'right',
                            title: {
                                display: true,
                                text: 'GBE'
                            }
                        }
                    ],
                    x: {
                        title: {
                            display: true,
                            text: 'FY'
                        }
                    }
                }
            }
        });

        function exportChart(chartId) {
            // Send the selected chart ID to export.php
            window.location.href = `export.php?chartId=${chartId}`;
        }



    </script>
</body>
</html>