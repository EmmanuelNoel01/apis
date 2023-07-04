<!DOCTYPE html>
<html>
<head>
    <title>View File</title>
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

        .file-viewer {
            margin-top: 20px;
        }

        .back-button {
            background-color: green;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>View File</h2>
    </div>

    <div class="content">
        <?php
        // Define the database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "hosptial";

        // Create a database connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the file ID is provided
        if (isset($_GET['id'])) {
            $file_id = $_GET['id'];

            // Prepare and execute the SQL query
            $stmt = $conn->prepare("SELECT * FROM medicalreport WHERE id = ?");
            $stmt->bind_param("i", $file_id);
            $stmt->execute();

            // Retrieve the file details
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $file_name = $row["file_name"];
                $file_data = $row["file_data"];

                // Determine the file extension
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

                // Display the file based on the extension
                if ($file_extension == 'docx' || $file_extension == 'doc') {
                    // Handle DOCX or DOC files
                    header('Content-Type: application/msword');
                    header('Content-Disposition: inline; filename="' . $file_name . '"');
                    header('Content-Length: ' . strlen($file_data));
                    echo $file_data;
                } elseif ($file_extension == 'pdf') {
                    // Handle PDF files
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: inline; filename="' . $file_name . '"');
                    header('Content-Length: ' . strlen($file_data));
                    echo $file_data;
                } else {
                    echo "Unsupported file format.";
                }
            } else {
                echo "File not found.";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "File ID not provided.";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

    <div class="header">
        <a class="back-button" href="reports.php">&lt; Back to Reports</a>
    </div>
</body>
</html>
