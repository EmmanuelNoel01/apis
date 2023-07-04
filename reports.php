<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <style>
        body {
            background-color: #f2f2f2;
        }

        .header {
            background-color: green;
            padding: 10px;
            color: white;
            text-align: center;
        }

        .content {
            margin: 20px;
        }

        .button {
            background-color: green;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin-right: 10px;
        }

        .file-list {
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-input {
            padding: 5px;
            width: 300px;
        }

        .filter-form {
            margin-bottom: 20px;
        }

        .filter-select {
            padding: 5px;
        }

        .bottom-bar {
            background-color: green;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            text-align: center;
            color: white;
        }

        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: green;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reports</h2>
        <a class="back-button" href="mobile_app.php">Back to Mobile App</a>
    </div>

    <div class="content">
        <form class="search-form" method="GET">
            <label for="search">Search by Name:</label>
            <input class="search-input" type="text" name="search" id="search">
            <input type="submit" value="Search">
        </form>

        <form class="filter-form" method="GET">
            <label for="order">Sort Order:</label>
            <select class="filter-select" name="order" id="order">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
            <input type="submit" value="Apply">
        </form>

        <div class="file-list">
            <table>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
                <?php

                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "hosptial";

                $conn = new mysqli($servername, $username, $password, $database);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $search = isset($_GET['search']) ? $_GET['search'] : '';

                $sql = "SELECT * FROM medicalreport";
                if (!empty($search)) {
                    $sql .= " WHERE file_name LIKE '%$search%'";
                }

                $order = isset($_GET['order']) ? $_GET['order'] : '';
                if ($order == 'asc') {
                    $sql .= " ORDER BY file_name ASC";
                } elseif ($order == 'desc') {
                    $sql .= " ORDER BY file_name DESC";
                }
                
                // Limit the number of files to display
                $sql .= " LIMIT 4";

                // Execute the query
                $result = $conn->query($sql);

                // Check if there are any files
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["file_name"] . "</td>";
                        echo "<td><a href=\"view_file.php?id=" . $row["id"] . "\">View</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan=\"2\">No files found.</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </table>
        </div>
    </div>

    <div class="bottom-bar">
        <a class="button" href="update_file.php">Update</a>
        <a class="button" href="view_file.php"></a>
    </div>
</body>
</html>
