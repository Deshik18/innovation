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
?>

<!DOCTYPE html>
<html>
<head>
    <title>List of SDG</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        table {
            border: 1px solid #ccc;
            width: 25%;
        }

        th {
            background-color: #ccc;
            text-align: center;
        }

        td {
            padding: 10px;
        }

        a {
            color: #333;
            text-decoration: none;
        }

        a:hover {
            color: #000;
            text-decoration: underline;
        }

        .nav-bar {
            float: left;
            width: 200px;
        }

        .content {
            float: left; /* Move the content to the left */
        }

        /* Clear the float to prevent content overlapping */
        .clear {
            clear: both;
        }

        .update-form {
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'nav1.php'; ?> <!-- Include the navigation bar -->

<!-- Existing content container with a unique class name -->
    <div class="content69">
    <?php
    $currentPage="sdg.php";
    // Database connection setup (replace with your own credentials)
    include 'config.php';

    // Handle row deletion
    if (isset($_GET['delete_id'])) {
        $deleteId = $_GET['delete_id'];
        $sqlDelete = "DELETE FROM gb_sdg WHERE sdg = '$deleteId'";
        if ($conn->query($sqlDelete) === TRUE) {
            echo "<script>alert('Deleted successfully.');</script>";
            header("Location: sdg.php");
        } else {
            echo "<script>alert('Error');</script>";      
            header("Location: sdg.php");      
        }
    }

    // Handle row update
    if (isset($_POST['update_year'])) {
        $updateYear = $_POST['update_year'];
        $currentYear = $_POST['current_year'];

        $sqlUpdate = "UPDATE gb_sdg SET sdg = '$updateYear' WHERE sdg = '$currentYear'";
        if ($conn->query($sqlUpdate) === TRUE) {
            echo "<script>alert('Year updated successfully.');</script>";
            header("Location: sdg.php");
        } else {
            echo "<script>alert('Error updating year: ');</script>";      
            header("Location: sdg.php");      
        }
    }

    if (isset($_POST['year'])) {
        $year = $_POST['year'];
        $sqlInsert = "INSERT INTO gb_sdg(sdg) VALUES ('$year')";
        if ($conn->query($sqlInsert) === TRUE) {
            echo "Succesful.";
            header("Location: sdg.php");
        } else {
            echo "Error";
            header("Location: sdg.php");
        }
    }

    // Retrieve data from the gb_fy table        
    $sql_sdg = "SELECT * FROM gb_sdg order by CAST(SUBSTRING(sdg, 5) AS UNSIGNED)";
    $result = $conn->query($sql_sdg);
    echo "<table>";
    echo "<tr><th>Year</th><th>Action</th><th>Action</th></tr>";
    ?>
    <!-- Form to insert a new year -->
    <div class="content"> <!-- Apply the content class to the form's container -->
        <h2>Insert New SDG</h2>
        <form method="post" action="">
            <label for="year">Year:</label>
            <input type="text" name="year" required>
            <input type="submit" value="Insert SDG">
        </form>
    </div>
    <?php

    while ($row = $result->fetch_assoc()) {
        $currentYear = $row["sdg"];
        echo "<tr>";
        echo "<td>$currentYear</td>";
        echo "<td><a href='?delete_id=" . $row["sdg"] . "'>Delete</a></td>";
        // Add an "Update" button that shows the form in the same row
        echo "<td><button onclick=\"showUpdateForm('$currentYear')\">Update</button></td>";
        echo "</tr>";
        // Add the update form (initially hidden) in the same row
        echo "<tr class='update-form' id='updateForm_$currentYear'>";
        echo "<td colspan='3'>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='current_year' value='$currentYear'>";
        echo "<label for='update_year'>New SDG:</label>";
        echo "<input type='text' name='update_year' required>";
        echo "<input type='submit' value='Update SDG:'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
    ?>
    </div>
    <div class="clear"></div>

    <script>
        // JavaScript function to show the update form for a specific year
        function showUpdateForm(year) {
            var updateForm = document.getElementById('updateForm_' + year);
            updateForm.style.display = 'table-row';
        }
    </script>

    <div class="clear"></div> <!-- Clear the float after the form -->
</body>
</html>
