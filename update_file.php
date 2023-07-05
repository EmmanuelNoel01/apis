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

        .file-preview {
            display: none;
            margin-bottom: 10px;
        }

        .file-preview img {
            max-width: 200px;
            max-height: 200px;
        }

        .back-button {
            background-color: green;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
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
        // PHP code for file update logic

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "hosptial";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $file = $_FILES['report'];
            $fileName = $file['name'];
            $fileData = file_get_contents($file['tmp_name']);

            // Check if the file already exists in the database
            $checkQuery = "SELECT * FROM medicalreport WHERE file_name = '$fileName'";
            $checkResult = $conn->query($checkQuery);
            if ($checkResult->num_rows > 0) {
                // Update the existing file
                $updateQuery = "UPDATE medicalreport SET file_data = '$fileData' WHERE file_name = '$fileName'";
                if ($conn->query($updateQuery) === TRUE) {
                    echo "File updated successfully.";
                } else {
                    echo "Error updating file: " . $conn->error;
                }
            } else {
                // Insert a new file
                $insertQuery = "INSERT INTO medicalreport (file_name, file_data) VALUES ('$fileName', '$fileData')";
                if ($conn->query($insertQuery) === TRUE) {
                    echo "File inserted successfully.";
                } else {
                    echo "Error inserting file: " . $conn->error;
                }
            }

            $conn->close();
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
