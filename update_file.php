<!DOCTYPE html>
<html>
<head>
    <title>Update File</title>
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

        label {
            font-weight: bold;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: green;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        .back-button {
            background-color: green;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
        }

        .file-preview {
            display: none;
            margin-bottom: 10px;
        }

        .file-preview img {
            max-width: 200px;
            max-height: 200px;
        }
    </style>
    <script>
        function previewFile() {
            var preview = document.querySelector('.file-preview');
            var file = document.querySelector('input[type=file]').files[0];
            var reader = new FileReader();

            reader.addEventListener("load", function () {
                var image = new Image();
                image.src = reader.result;
                preview.innerHTML = "";
                preview.appendChild(image);
                preview.style.display = "block";
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <a class="back-button" href="reports.php">&lt; Back to Reports</a>
    </div>

    <div class="content">
        <h2>Update File</h2>

        <?php
        // Check if the file IDs are provided
        if (isset($_POST['selected_files'])) {
            // Retrieve the selected file IDs
            $selected_files = $_POST['selected_files'];

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

            foreach ($selected_files as $file_id) {
                // Retrieve the file details from the database
                $stmt = $conn->prepare("SELECT file_name, file_data FROM medicalreport WHERE id = ?");
                $stmt->bind_param("i", $file_id);
                $stmt->execute();
                $stmt->bind_result($file_name, $file_data);
                $stmt->fetch();
                $stmt->close();

                // Check if the file exists
                if ($file_name) {
                    // Check if the form was submitted
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Check if a new file was uploaded
                        if ($_FILES["report"]["size"] > 0) {
                            // Retrieve the updated file contents
                            $updated_file_data = $_FILES["report"]["tmp_name"];

                            // Update the file data in the database
                            $stmt = $conn->prepare("UPDATE medicalreport SET file_data = ? WHERE id = ?");
                            $stmt->bind_param("si", $updated_file_data, $file_id);
                            $stmt->send_long_data(0, file_get_contents($updated_file_data));
                            $stmt->execute();
                            $stmt->close();
                        }
                    }
                } else {
                    // File not found
                    echo "File not found for ID: " . $file_id;
                }
            }

            // Redirect to the reports page after updating the files
            header("Location: reports.php");
            exit;

            // Close the database connection
            $conn->close();
        } else {
            // File IDs not provided
            echo "";
        }
        ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="report">Select File:</label>
            <input type="file" name="report" id="report" required onchange="previewFile()"><br>
            <div class="file-preview"></div>
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
