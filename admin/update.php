<?php
session_start();

// Check if the user is not authenticated (e.g., not logged in)
if (!isset($_SESSION['user_id'])) {
    // Display a JavaScript alert message
    echo '<script>alert("You can\'t access this page. Please log in.");</script>';
    
    // Add a delay before redirecting (e.g., 3 seconds)
    echo '<meta http-equiv="refresh" content="3;url=../index.php">';

    // Exit to prevent further PHP execution
    exit();
}
$var = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cell</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 150vh;
        }

        .container {
            width: 1000px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 700px;
        }

        .message-box {
            padding: 40px;
            margin-right: 500px;
            border-bottom: 1px solid #ccc;
            position: relative;
            background-color: lightcoral;
            color: white;
        }

        .message-box:hover {
            background-color: coral;
        }

        .message-box:before {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 10px;
            border-style: solid;
            border-width: 10px 10px 0;
            border-color: lightcoral transparent transparent transparent;
        }

        .update-options {
            padding: 30px;
            margin-left: 500px;
            font-size: 20px;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        select[multiple] {
            height: 200px;
        }

        .update-button {
            width: 25%;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            margin-left: 175px;
        }

        .update-button:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background-color: #45A049;
            transition: width 0.3s ease;
            z-index: -1;
        }

        .update-button:hover:before {
            width: 100%;
        }

        .update-button-text {
            position: center;
            z-index: 1;
            padding: 10px 20px;
        }

        .back-button {
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            position: absolute;
            top: 10px;
            left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .back-button:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message-box">
            <?php
            // Get the row ID and column name from the URL
            $rowId = $_GET['rowId'];
            $column = $_GET['column'];
            include 'config.php'; // Include your database connection
            // Fetch old values from the database based on $rowId and $column
            $sqlOldValue = "SELECT $column FROM gb_data WHERE sno = $rowId";
            $resultOldValue = $conn->query($sqlOldValue);
            // Define an array of columns that require numeric input
            $numericColumns = ['ab', 'agb', 'rb', 'rgb', 'be', 'gbe'];

            $sql = "";
            $label = ''; // Initialize $label with an empty string

            if ($column === 'fy') {
                $sql = "SELECT fy FROM gb_fy";
                $label = 'FY';
            } elseif ($column === 'category') {
                $sql = "SELECT category FROM gb_cat";
                $label = 'Category';
            } elseif ($column === 'scheme_name') {
                $sql = "SELECT scheme_name FROM gb_scheme_name";
                $label = 'Scheme Name';
            } elseif ($column === 'scheme_code') {
                $sql = "SELECT scheme_code FROM gb_scheme_code";
                $label = 'Scheme Code';
            } elseif ($column === 'dept') {
                $sql = "SELECT dept_name FROM gb_dept";
                $label = 'Department';
            } else if ($column === 'ab') {
                $label = 'AB';
            } else if ($column === 'agb') {
                $label = 'AGB';
            } else if ($column === 'rb') {
                $label = 'RB';
            } else if ($column === 'rgb') {
                $label = 'RGB';
            } else if ($column === 'be') {
                $label = 'BE';
            } else if ($column === 'gbe') {
                $label = 'GBE';
            } else if ($column === 'activity') {
                $label = 'Activity';
            } else if ($column === 'sdg') {
                $label = 'SDG';
            } else if ($column === 'scheme_major_obj') {
                $label = 'Scheme Major Objective';
            }
            if ($resultOldValue->num_rows > 0) {
                while ($row = $resultOldValue->fetch_assoc()) {
                    $oldValue = $row[$column];
                    echo 'Old ' . $label . ': ';
                    echo $oldValue;
                }
            }
            if($column === 'dept'){
                $column = 'dept_name';
            }
            ?>
        </div>
        <a href="display.php" class="back-button">&#9664;</a>
        <div class="update-options">
            <?php
            echo '<form method="post">';
            echo "<label for=\"$column\">Update New $label:</label>";
            if (in_array($column, $numericColumns)) {
                // Display a numeric input field for numeric columns
                $currentValue = 0; // Default value if no value is found
                echo "<input type=\"number\" id=\"$column\" name=\"$column\" value=\"$currentValue\">";
            } elseif ($column === 'scheme_major_obj') {
                // Display a textarea for the scheme_major_obj column
                $currentValue = ''; // Default value if no value is found
                echo "<textarea id=\"$column\" name=\"$column\">$currentValue</textarea>";
            } elseif ($column === 'activity') {
                $sql = "SELECT activity FROM gb_activity";
                $result = $conn->query($sql);
                $currentValues = []; // Default value if no value is found
                
                // Retrieve the current values for the row from your database
                // Replace this with your code to fetch the current values from the database
                $sql_selected_activity = "SELECT activity FROM gb_data WHERE sno = $rowId";
                $result_selected_activity = $conn->query($sql_selected_activity);
                if ($result_selected_activity->num_rows > 0) {
                    while ($row = $result_selected_activity->fetch_assoc()) {
                        $currentValues[] = $row['activity'];
                    }
                }
                echo "<br>";
                while ($row = $result->fetch_assoc()) {
                    $option = $row['activity'];
                    $isChecked = in_array($option, $currentValues) ? 'checked' : '';
                    echo "<input type=\"checkbox\" name=\"activity[]\" value=\"$option\" $isChecked> $option<br>";
                }
            } elseif ($column === 'sdg') {
                $sql = "SELECT sdg FROM gb_sdg";
                $result = $conn->query($sql);
                $currentValues = []; // Default value if no value is found

                // Retrieve the current values for the row from your database
                // Replace this with your code to fetch the current values from the database
                $sql_selected_sdg = "SELECT sdg FROM gb_data WHERE sno = $rowId";
                $result_selected_sdg = $conn->query($sql_selected_sdg);
                if ($result_selected_sdg->num_rows > 0) {
                    while ($row = $result_selected_sdg->fetch_assoc()) {
                        $currentValues[] = $row['sdg'];
                    }
                }
                echo "<br>";
                while ($row = $result->fetch_assoc()) {
                    $option = $row['sdg'];
                    $isChecked = in_array($option, $currentValues) ? 'checked' : '';
                    echo "<input type=\"checkbox\" name=\"sdg[]\" value=\"$option\" $isChecked> $option<br>";
                }
            } else {
                // Display a select input field for non-numeric columns
                echo "<select id=\"$column\" name=\"$column\">";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $option = $row[$column];
                    echo "<option value=\"$option\">$option</option>";
                }
                echo '</select>';
            }
            echo '<button type="submit" name="update" class="update-button"><span class="update-button-text">&#9658; Update</span></button>';
            echo '</form>';
            if (isset($_POST['update'])) {
                // Get the new value from the form
                if ($column === 'activity') {
                    $newValue = implode(', ', $_POST['activity'] ?? []);
                } elseif ($column === 'sdg') {
                    $newValue = implode(', ', $_POST['sdg'] ?? []);
                } else {
                    $newValue = $_POST[$column];
                }

                // If numeric value not given, make it 0
                if (in_array($column, $numericColumns) && !is_numeric($newValue)) {
                    $newValue = 0;
                }

                // Update the database with the new value
                // Replace this with your code to update the database
                // For example, you might use SQL to update the specific column for the row
                // Here's a basic example:
                // $sql = "UPDATE gb_data SET $column = '$newValue' WHERE sno = $rowId";
                if ($column === 'dept_name') {
                    $column = 'dept';
                }
                $sql = "UPDATE gb_data SET $column = '$newValue' WHERE sno = $rowId";
                $result = $conn->query($sql);
                $sql = "UPDATE gb_data SET user = '$var' WHERE sno = $rowId";
                $result = $conn->query($sql);

                // Execute the SQL query to update the database

                // After updating, you can redirect back to the table or a success page
                // For example:
                // exit();
            }
            ?>
            <script>
                setTimeout(function() {
                    window.location.href = "display.php"; // Navigate to display.php after 10 seconds
                }, 10000); // Refresh after 10 seconds (10000 milliseconds)
            </script>
        </div>
    </div>
</body>
</html>
