<!DOCTYPE html>
<html>
<head>
    <title>Upload Medical Report</title>
</head>
<body>
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

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve file details
        $file_name = $_FILES["report"]["name"];
        $file_tmp = $_FILES["report"]["tmp_name"];

        // Prepare and execute the SQL query
        $stmt = $conn->prepare("INSERT INTO medicalreport (file_name, file_data) VALUES (?, ?)");
        $stmt->bind_param("ss", $file_name, $file_data);
        $file_data = file_get_contents($file_tmp);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->affected_rows > 0) {
            echo "File uploaded successfully!";
        } else {
            echo "Error uploading file.";
        }

        // Close the statement
        $stmt->close();
    }
    ?>

    <h2>Upload Medical Report</h2>

    <form method="POST" enctype="multipart/form-data">
        <label for="report">Select File:</label>
        <input type="file" name="report" id="report" required><br><br>
        <input type="submit" value="Upload">
    </form>
</body>
</html>
