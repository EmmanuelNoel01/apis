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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data sent from the Flutter app
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    // Extract the login credentials from the JSON data
    $healthWorkerID = $data->healthWorkerID; // Assuming the field name is "healthWorkerID"

    // Prepare the SQL query to check if the user exists in the "doctor" table
    $sql = "SELECT * FROM doctor WHERE healthWorkerID = ?";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $healthWorkerID);

    // Execute the prepared statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the user exists and the login is successful
    if ($result->num_rows === 1) {
        // User exists, login successful
        $response = array('success' => true, 'message' => 'Login successful');
    } else {
        // User does not exist or login failed
        $response = array('success' => false, 'message' => 'Login failed');
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Invalid request method
    $response = array('success' => false, 'message' => 'Invalid request method');
}

// Close the database connection
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
