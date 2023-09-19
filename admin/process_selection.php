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
$user_id = $_SESSION['user_id'];
// Check if the required POST variables are set
if (
    isset($_POST['fy']) &&
    isset($_POST['category']) &&
    isset($_POST['department']) &&
    isset($_POST['scheme_name']) &&
    isset($_POST['scheme_code']) &&
    isset($_POST['ab']) &&
    isset($_POST['agb']) &&
    isset($_POST['rb']) &&
    isset($_POST['rgb']) &&
    isset($_POST['be']) &&
    isset($_POST['gbe']) &&
    isset($_POST['scheme_major_obj']) &&
    isset($_POST['sdg']) &&
    isset($_POST['activity'])
) {
    // Database connection setup (replace with your own credentials)
    include 'config.php'; // Include your database connection file

    // Sanitize and prepare the values for insertion
    $fy = mysqli_real_escape_string($conn, $_POST['fy']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $scheme_name = mysqli_real_escape_string($conn, $_POST['scheme_name']);
    $scheme_code = mysqli_real_escape_string($conn, $_POST['scheme_code']);
    $ag = (float)$_POST['ab'];
    $gb = (float)$_POST['agb'];
    $rb = (float)$_POST['rb'];
    $rgb = (float)$_POST['rgb'];
    $be = (float)$_POST['be'];
    $gbe = (float)$_POST['gbe'];
    $scheme_major_obj = mysqli_real_escape_string($conn, $_POST['scheme_major_obj']);
    $sdg = implode(",", $_POST['sdg']); // Convert array to comma-separated string
    $activity = implode(",", $_POST['activity']); // Convert array to comma-separated string

    // SQL query to insert the data into the gb_data table   ag	agb	rb	rgb	be	gbe
    $sql = "INSERT INTO gb_data (fy, category, dept, scheme_name, scheme_code, ab, agb, rb, rgb, be, gbe, scheme_major_obj, sdg, activity, user)
            VALUES ('$fy', '$category', '$department', '$scheme_name', '$scheme_code', $ag, $gb, $rb, $rgb, $be, $gbe, '$scheme_major_obj', '$sdg', '$activity', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        // Insertion was successful
        echo "Data inserted successfully.";
        header("Location: insert.php");
    } else {
        // Handle insertion error
        echo "Error inserting data: ";
        header("Location: insert.php");
    }
} else {
    // Handle missing POST variables
    echo "Incomplete data received.";
    header("Location: insert.php");

}
?>
