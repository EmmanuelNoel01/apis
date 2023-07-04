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

// Retrieve the prescription data from the patients table
$sql = "SELECT prescription, days, name FROM patients";
$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prescription = (int)$row['prescription']; // Cast to integer
        $days = $row['days'];
        $name = $row['name'];

        try {
            // Calculate the interval between doses based on the prescription
            $dose_interval = 24 / $prescription;

            // Check if the patient still has days left for medication
            if ($days > 0) {
                // Compare the name with the users table
                $users_sql = "SELECT name FROM users";
                $users_result = $conn->query($users_sql);

                $matched_user = false;

                if ($users_result->num_rows > 0) {
                    while ($user_row = $users_result->fetch_assoc()) {
                        $user_name = $user_row['name'];

                        // Compare the names letter by letter and length
                        if (strcasecmp($name, $user_name) === 0) {
                            $matched_user = true;
                            break;
                        }
                    }
                }

                // If a matching user is found, send the reminder message
                if ($matched_user) {
                    $remaining_tablets = $days * $prescription;
                    $days_left = $days - 1;

                    $message = "Dear " . $name . ", please take " . $prescription . " tablets. You have " . $remaining_tablets . " tablets left. Days left to finish: " . $days_left + 1 . ".";

                    // Update the remaining days for the patient in the patients table
                    $updated_days = $days - 1;
                    $update_sql = "UPDATE patients SET days = $updated_days WHERE name = '$name'";
                    $conn->query($update_sql);

                    // Add each message as an individual item to the response array
                    $response[] = $message;
                }
            }
        } catch (DivisionByZeroError $e) {
            // Ignore the error and continue processing the next patient
            continue;
        }
    }
} else {
    $response[] = "No data found.";
}

// Close the database connection
$conn->close();

// Set the JSON response header and echo the response
header('Content-Type: application/json');
echo json_encode($response);
?>
