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

<head>
    <title>Select Options</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #333;
        }

        label {
            font-weight: bold;
            font-size: 18px;
        }

        select,
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        label[for="selected_sdg"],
        label[for="selected_activity"] {
            display: block;
            font-size: 18px;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        .container {
            max-width: 800px;
            width: 75%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-submit {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<?php include 'nav1.php'; ?> 
<div class="content69">
<?php
include 'config.php';
?>
<body>
<div class="container">
    <h2>Select Options</h2>
    <form method="post" action="process_selection.php">
        <label for="financial_year">Financial Year:</label>
        <select name="fy">
            <?php
            // Fetch and display options from the gb_fy table
            $sqlFy = "SELECT fy FROM gb_fy order by fy";
            $resultFy = $conn->query($sqlFy);

            while ($rowFy = $resultFy->fetch_assoc()) {
                echo "<option value='" . $rowFy['fy'] . "'>" . $rowFy['fy'] . "</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="category">Category:</label>
        <select name="category">
            <?php
            // Fetch and display options from the gb_cat table
            $sqlCat = "SELECT category FROM gb_cat order by category";
            $resultCat = $conn->query($sqlCat);

            while ($rowCat = $resultCat->fetch_assoc()) {
                echo "<option value='" . $rowCat['category'] . "'>" . $rowCat['category'] . "</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="department">Department:</label>
        <select name="department">
            <?php
            // Fetch and display options from the gb_dept table
            $sqlDept = "SELECT dept_name FROM gb_dept order by dept_name";
            $resultDept = $conn->query($sqlDept);

            while ($rowDept = $resultDept->fetch_assoc()) {
                echo "<option value='" . $rowDept['dept_name'] . "'>" . $rowDept['dept_name'] . "</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="scheme_name">Scheme Name:</label>
        <select name="scheme_name">
            <?php
            // Fetch and display options from the gb_scheme_name table
            $sqlSchemeName = "SELECT scheme_name FROM gb_scheme_name order by scheme_name";
            $resultSchemeName = $conn->query($sqlSchemeName);

            while ($rowSchemeName = $resultSchemeName->fetch_assoc()) {
                echo "<option value='" . $rowSchemeName['scheme_name'] . "'>" . $rowSchemeName['scheme_name'] . "</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="scheme_code">Scheme Code:</label>
        <select name="scheme_code">
            <?php
            // Fetch and display options from the gb_scheme_code table
            $sqlSchemeCode = "SELECT scheme_code FROM gb_scheme_code order by scheme_code";
            $resultSchemeCode = $conn->query($sqlSchemeCode);

            while ($rowSchemeCode = $resultSchemeCode->fetch_assoc()) {
                echo "<option value='" . $rowSchemeCode['scheme_code'] . "'>" . $rowSchemeCode['scheme_code'] . "</option>";
            }
            ?>

        </select>
        <br><br>

        <!-- Add input fields for budget values (using type="number") -->
        <label for="ab_budget">AB Budget:</label>
        <input type="number" name="ab" step="0.01" value="0" required>
        <br><br>

        <label for="agb_budget">AGB Budget:</label>
        <input type="number" name="agb" step="0.01" value="0" required>
        <br><br>

        <label for="rb_budget">RB Budget:</label>
        <input type="number" name="rb" step="0.01" value="0" required>
        <br><br>

        <label for="rgb_budget">RGB Budget:</label>
        <input type="number" name="rgb" step="0.01" value="0" required>
        <br><br>

        <label for="be_budget">BE Budget:</label>
        <input type="number" name="be" step="0.01" value="0" required>
        <br><br>

        <label for="gbe_budget">GBE Budget:</label>
        <input type="number" name="gbe" step="0.01" value="0" required>
        <br><br>


        <label for="scheme_major_obj">Scheme Major Objective:</label>
        <textarea name="scheme_major_obj" id="scheme_major_obj" rows="4" cols="50" maxlength="4000"></textarea>
        <br><br>

        <label>Select SDGs:</label><br>
        <?php
        $sql_sdg = "SELECT * FROM gb_sdg order by CAST(SUBSTRING(sdg, 5) AS UNSIGNED)";
        $result_sdg = $conn->query($sql_sdg);

        while ($row_sdg = $result_sdg->fetch_assoc()) {
        $sdgId = $row_sdg['sdg'];
        $sdgName = $row_sdg['sdg'];

        // Generate a checkbox for each SDG row
        echo "<input type='checkbox' name='sdg[]' value='$sdgId'> $sdgName<br>";
        }
        ?>
        <br><br>

        <label>Select Activity:</label><br>
        <?php
        $sql_sdg = "SELECT * FROM gb_activity order by activity";
        $result_sdg = $conn->query($sql_sdg);

        while ($row_sdg = $result_sdg->fetch_assoc()) {
        $activityId = $row_sdg['activity'];
        $activityName = $row_sdg['activity'];

        // Generate a checkbox for each SDG row
        echo "<input type='checkbox' name='activity[]' value='$activityId'> $activityName<br>";
        }
        ?>
        <br><br>
        <input type="submit" value="Submit">
    </form>
    </div>
    </div>
</body>
</html>
