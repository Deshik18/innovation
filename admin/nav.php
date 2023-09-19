<!DOCTYPE html>
<html>
<head>
    <title>Navigation</title>
    <style>
        /* Basic styling for the navigation links */
        .nav-link {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: blueviolet;
            font-size: 16px;
            font-weight: bold;
            width: 90%;
        }

        /* Add a hover effect to the navigation links */
        .nav-link:hover {
            color: red;
            background-color: #ddd;
        }

        /* Align the navigation links vertically */
        .nav-link {
            float: none;
            display: inline-block;
        }

        /* Styling for the navigation bar */
        .nav-bar {
            display: flex;
            flex-direction: column;
            border: 8px solid aquamarine; /* Border style and color */
            padding: 10px; /* Add some padding to the navigation bar */
            background-color: transparent;
        }

        /* Highlight the current page link */
        .current-page {
            color: #fff;
            background-color: coral; /* Highlight color */
        }
    </style>
</head>
<body>
    <div class="nav-bar">
        <a class="nav-link <?php if ($currentPage === 'fy.php') echo 'current-page'; ?>" href="fy.php">FY</a>
        <a class="nav-link <?php if ($currentPage === 'cat.php') echo 'current-page'; ?>" href="cat.php">Category</a>
        <a class="nav-link <?php if ($currentPage === 'dept.php') echo 'current-page'; ?>" href="dept.php">Department</a>
        <a class="nav-link <?php if ($currentPage === 'schemename.php') echo 'current-page'; ?>" href="schemename.php">Scheme Name</a>
        <a class="nav-link <?php if ($currentPage === 'schcode.php') echo 'current-page'; ?>" href="schcode.php">Scheme Code</a>
        <a class="nav-link <?php if ($currentPage === 'sdg.php') echo 'current-page'; ?>" href="sdg.php">SDG</a>
        <a class="nav-link <?php if ($currentPage === 'activity.php') echo 'current-page'; ?>" href="activity.php">Activity</a>
        <a class="nav-link <?php if ($currentPage === 'insert.php') echo 'current-page'; ?>" href="insert.php">Insert Data</a>
        <!-- Logout link -->
        <a class="nav-link" href="logout.php">Logout</a>
    </div>
</body>
</html>
