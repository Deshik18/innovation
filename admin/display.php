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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <title>Table Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 150%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
        }

        table thead {
            background-color: #333;
            color: #fff;
        }

        table th {
            font-weight: bold;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tbody tr td:hover {
            background-color: lightcoral;
            color: white;
        }

        .marquee-container {
            position: absolute;
            top: 43px;
            left: 0;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            overflow: hidden;
        }

        .marquee-text {
            animation: marquee 10s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
    </style>
</head>
    <body>
    <?php include 'nav1.php'; ?> <!-- Include the navigation bar -->

<!-- Existing content container with a unique class name -->
    <div class="content69">
    <div class="marquee-container">
        <div class="marquee-text">Double click a cell to edit</div>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th scope="col">FY</th>
                    <th scope="col">Category</th>
                    <th scope="col">Department</th>
                    <th scope="col">Scheme Name</th>
                    <th scope="col">Scheme Code</th>
                    <th scope="col">AB</th>
                    <th scope="col">AGB</th>
                    <th scope="col">RB</th>
                    <th scope="col">RGB</th>
                    <th scope="col">BE</th>
                    <th scope="col">GBE</th>
                    <th scope="col">Scheme Major Objective</th>
                    <th scope="col">SDG</th>
                    <th scope="col">Activity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config.php';
                $sql = "SELECT * FROM gb_data order by fy";
                $result = $conn->query($sql);

                // Fetch data as an associative array
                $data = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                }
                foreach ($data as $row) {
                    echo '<tr data-row-id="' . $row['sno'] . '">';
                    echo '<td data-column="fy">' . $row['fy'] . '</td>';
                    echo '<td data-column="category">' . $row['category'] . '</td>';
                    echo '<td data-column="dept">' . $row['dept'] . '</td>';
                    echo '<td data-column="scheme_name">' . $row['scheme_name'] . '</td>';
                    echo '<td data-column="scheme_code">' . $row['scheme_code'] . '</td>';
                    echo '<td data-column="ab">' . $row['ab'] . '</td>';
                    echo '<td data-column="agb">' . $row['agb'] . '</td>';
                    echo '<td data-column="rb">' . $row['rb'] . '</td>';
                    echo '<td data-column="rgb">' . $row['rgb'] . '</td>';
                    echo '<td data-column="be">' . $row['be'] . '</td>';
                    echo '<td data-column="gbe">' . $row['gbe'] . '</td>';
                    echo '<td data-column="scheme_major_obj">' . $row['scheme_major_obj'] . '</td>';
                    echo '<td data-column="sdg">' . $row['sdg'] . '</td>';
                    echo '<td data-column="activity">' . $row['activity'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
    <script>
        const table = document.querySelector('table');
        table.addEventListener('dblclick', function (event) {
            const cell = event.target;
            const column = cell.getAttribute('data-column');
            const rowId = cell.closest('tr').getAttribute('data-row-id');
            const editUrl = `update.php?rowId=${rowId}&column=${column}`;
            window.location.href = editUrl;
        });

        // Function to handle row deletion
        function deleteRow(rowId) {
            const confirmation = prompt("Are you sure you want to delete this row? (Y/N)");
            if (confirmation && (confirmation.toLowerCase() === 'y')) {
                // User confirmed deletion
                const deleteUrl = `delete.php?rowId=${rowId}`;
                fetch(deleteUrl)
                    .then(response => response.text())
                    .then(result => {
                        alert(result); // Show the result in an alert
                        // Reload the page to reflect the changes
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the row.');
                    });
            }
        }
        // Add a delete button to each row
        const rows = document.querySelectorAll('tr[data-row-id]');
        rows.forEach(row => {
            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Delete';
            deleteButton.addEventListener('click', () => {
                const rowId = row.getAttribute('data-row-id');
                deleteRow(rowId);
            });
            const cell = document.createElement('td');
            cell.appendChild(deleteButton);
            row.appendChild(cell);
        });
    </script>
</body>
</html>
