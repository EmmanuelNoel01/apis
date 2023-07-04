<?php

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hosptial";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $response = array('status' => 'success');
        echo json_encode($response);
        exit; // Terminate further execution

    } else {
        $response = array('status' => 'failure');
        echo json_encode($response);
        exit; // Terminate further execution
    }
}

$conn->close();

?>
