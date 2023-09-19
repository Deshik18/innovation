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
    <title>List of Departments</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <style>
        body {
            background-color: #f5f5f5;
        }

        table {
            border: 1px solid #ccc;
            width: 25%;
            float: left; /* Move the table to the left */
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
    $currentPage="dept.php";
    // Database connection setup (replace with your own credentials)
    include 'config.php';

    // Handle row deletion
    if (isset($_GET['delete_id'])) {
        $deleteId = $_GET['delete_id'];
        $sqlDelete = "DELETE FROM gb_dept WHERE dept_name = '$deleteId'";
        if ($conn->query($sqlDelete) === TRUE) {
            echo "<script>alert('Department deleted successfully.');</script>";
            header("Location: dept.php");
        } else {
            echo "<script>alert('Error deleting.');</script>";      
            header("Location: dept.php");      
        }
    }

    // Handle row update
    if (isset($_POST['update_dept'])) {
        $updateDept = $_POST['update_dept'];
        $currentDept = $_POST['current_dept'];

        $sqlUpdate = "UPDATE gb_dept SET dept_name = '$updateDept' WHERE dept_name = '$currentDept'";
        if ($conn->query($sqlUpdate) === TRUE) {
            echo "<script>alert('Department updated successfully.');</script>";
            header("Location: dept.php");
        } else {
            echo "<script>alert('Error updating department: ');</script>";      
            header("Location: dept.php");      
        }
    }

    // Handle department insertion
    if (isset($_POST['dept_name'])) {
        $deptName = $_POST['dept_name'];
        $sqlInsert = "INSERT INTO gb_dept (dept_name) VALUES ('$deptName')";
        if ($conn->query($sqlInsert) === TRUE) {
            echo "Department inserted successfully.";
            header("Location: dept.php");
        } else {
            echo "Error inserting department: ";
            header("Location: dept.php");
        }
    }
    ?>
    <div class="content"> <!-- Apply the content class to the form's container -->
        <h2>Insert New Department</h2>
        <form method="post" action="">
            <label for="dept_name">Department Name:</label>
            <input type="text" name="dept_name" required>
            <input type="submit" value="Insert Department">
        </form>
    </div>
    <?php

    // Retrieve data from the gb_dept table
    $sqlSelect = "SELECT * FROM gb_dept order by dept_name";
    $result = $conn->query($sqlSelect);
    echo "<table>";
    echo "<tr><th>Department</th><th>Action</th><th>Action</th></tr>";

    while ($row = $result->fetch_assoc()) {
        $currentDept = $row["dept_name"];
        echo "<tr>";
        echo "<td>$currentDept</td>";
        echo "<td><a href='?delete_id=$currentDept'>Delete</a></td>";
        // Add an "Update" button that shows the form in the same row
        echo "<td><button onclick=\"showUpdateForm('$currentDept')\">Update</button></td>";
        echo "</tr>";
        // Add the update form (initially hidden) in the same row
        echo "<tr class='update-form' id='updateForm_$currentDept'>";
        echo "<td colspan='3'>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='current_dept' value='$currentDept'>";
        echo "<label for='update_dept'>New Department:</label>";
        echo "<input type='text' name='update_dept' required>";
        echo "<input type='submit' value='Update Department'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
    ?>
    </div>
    <div class="clear"></div>

    <!-- Form to insert a new department -->
    

    <script>
        // JavaScript function to show the update form for a specific department
        function showUpdateForm(dept) {
            var updateForm = document.getElementById('updateForm_' + dept);
            updateForm.style.display = 'table-row';
        }
    </script>

    <div class="clear"></div> <!-- Clear the float after the form -->
</body>
</html>
