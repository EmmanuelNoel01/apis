<!DOCTYPE html>
<html>
<head>
    <title>Disease Count</title>
    <style>
        body {
            background-color: #f2f2f2;
        }

        .content {
            margin: 20px;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>Disease Count</h2>

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

        // Define the diseases to count
        $diseases = ['malaria', 'typhoid', 'hiv/aids'];

        // Initialize an array to store the disease counts for each month
        $diseaseCountsByMonth = [];

        // Retrieve the disease data from the patients table
        $sql = "SELECT disease, month FROM patients";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $disease = strtolower($row['disease']);
                $month = strtolower($row['month']);

                // Increment the count for the specific disease and month
                if (in_array($disease, $diseases)) {
                    if (!isset($diseaseCountsByMonth[$month][$disease])) {
                        $diseaseCountsByMonth[$month][$disease] = 1;
                    } else {
                        $diseaseCountsByMonth[$month][$disease]++;
                    }
                }
            }

            // Prepare the data for the chart
            $labels = array_keys($diseaseCountsByMonth);
            $sortedLabels = array_map('strtotime', $labels);
            array_multisort($sortedLabels, SORT_ASC, $labels);

            $datasets = [];

            foreach ($diseases as $disease) {
                $data = [];
                foreach ($diseaseCountsByMonth as $monthData) {
                    $count = isset($monthData[$disease]) ? $monthData[$disease] : 0;
                    $data[] = $count;
                }

                $datasets[] = [
                    'label' => ucfirst($disease),
                    'backgroundColor' => $disease === 'malaria' ? 'blue' : ($disease === 'hiv/aids' ? 'red' : 'green'),
                    'data' => $data
                ];
            }

            // Display the chart
            echo '<canvas id="chart"></canvas>';

            // Close the database connection
            $conn->close();
            ?>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                var ctx = document.getElementById('chart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: <?php echo json_encode($datasets); ?>
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Months'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Diseases Tested'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    generateLabels: function (chart) {
                                        var data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.datasets.map(function (dataset, i) {
                                                return {
                                                    text: dataset.label,
                                                    fillStyle: dataset.backgroundColor,
                                                    hidden: !chart.isDatasetVisible(i),
                                                    lineCap: dataset.borderCapStyle,
                                                    lineDash: dataset.borderDash,
                                                    lineDashOffset: dataset.borderDashOffset,
                                                    lineJoin: dataset.borderJoinStyle,
                                                    lineWidth: dataset.borderWidth,
                                                    strokeStyle: dataset.borderColor,
                                                    pointStyle: dataset.pointStyle,
                                                    rotation: dataset.labelRotation,
                                                    textAlign: dataset.textAlign,
                                                    font: dataset.font,
                                                };
                                            });
                                        }
                                        return [];
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        <?php } else {
            echo "No data found.";
        } ?>
    </div>
</body>
</html>
