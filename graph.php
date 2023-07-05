<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hosptial";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve disease occurrences
$query_disease = "SELECT LOWER(disease) AS disease, LOWER(month) AS month, COUNT(*) AS occurrence FROM patients GROUP BY LOWER(disease), LOWER(month) ORDER BY FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
$result_disease = $conn->query($query_disease);

// Query to retrieve division information
$query_division = "SELECT LOWER(division) AS division, COUNT(*) AS total_tested FROM patients GROUP BY LOWER(division)";
$result_division = $conn->query($query_division);

// Prepare the data for display (disease occurrences)
$data_disease = array();
$labels = array();
$disease_data = array();
while ($row = $result_disease->fetch_assoc()) {
    $disease = ucfirst($row["disease"]);
    $month = ucfirst($row["month"]);
    $occurrence = $row["occurrence"];
    if (!in_array($month, $labels)) {
        $labels[] = $month;
    }
    if (!isset($disease_data[$disease])) {
        $disease_data[$disease] = array();
    }
    $disease_data[$disease][$month] = $occurrence;
}

// Prepare the data for display (division information)
$data_division = array();
while ($row = $result_division->fetch_assoc()) {
    $division = ucfirst($row["division"]);
    $total_tested = $row["total_tested"];
    $data_division[$division] = $total_tested;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Disease Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 40px;
            margin-bottom: 20px;
        }

        canvas {
            max-width: 100%;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Disease Statistics</h1>

    <h2>Disease Occurrences by Month</h2>
    <canvas id="diseaseChart"></canvas>
    <script>
        var ctx = document.getElementById("diseaseChart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [
                    <?php foreach ($disease_data as $disease => $data) { ?>
                    {
                        label: "<?php echo $disease; ?>",
                        data: <?php echo json_encode(array_values($data)); ?>,
                        backgroundColor: randomColor(),
                    },
                    <?php } ?>
                ]
            },
            options: {
                responsive: true,
                indexAxis: 'x',
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Disease Occurrences by Month'
                    }
                }
            }
        });

        // Generate a random color for the bar graph
        function randomColor() {
            var letters = "0123456789ABCDEF";
            var color = "#";
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>

    <h2>Division Information</h2>
    <?php if (!empty($data_division)) { ?>
        <table>
            <tr>
                <th>Division</th>
                <th>Total Tested</th>
            </tr>
            <?php foreach ($data_division as $division => $total_tested) { ?>
                <tr>
                    <td><?php echo $division; ?></td>
                    <td><?php echo $total_tested; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>No division information available.</p>
    <?php } ?>
</body>
</html>
