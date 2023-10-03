<?php
include 'config.php';
// Initialize initial state
$initialState = false;

// Retrieve the initial state from the database
$sql = "SELECT is_on FROM server"; // Assuming you have only one row in the table
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $initialState = (bool) $row["is_on"];
}

// Handle form submission
if (isset($_POST['confirm'])) {
    $isOn = $initialState ? 0 : 1;

    // Update the database with the new state
    $updateSql = "UPDATE server SET is_on = $isOn"; // Assuming you have only one row in the table

    if ($conn->query($updateSql) === TRUE) {
        $initialState = !$initialState; // Update the initial state based on the new value
    } else {
        echo "Error updating state: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            margin-top: 100px;
        }

        .status {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .btn-container {
            margin-top: 20px;
        }

        .btn {
            font-size: 18px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .btn-yes {
            background-color: #4CAF50;
            color: white;
        }

        .btn-yes:hover {
            background-color: #45a049;
        }

        .btn-no {
            background-color: #f44336;
            color: white;
        }

        .btn-no:hover {
            background-color: #d32f2f;
        }

        .link {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="status">Server is <?php echo $initialState ? 'on' : 'off'; ?></p>
        <form method="post" class="btn-container">
            <p class="confirmation">Do you want to change the server state to <?php echo $initialState ? 'off' : 'on'; ?>?</p>
            <button type="submit" name="confirm" class="btn btn-yes">Yes</button>
            <button type="submit" name="confirm" class="btn btn-no">No</button>
        </form>
        <a href="insert.php" class="link">Go to Insert Page</a>
    </div>
</body>
</html>

