<?php
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "hosptial";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Validate form data
if (empty($name) || empty($email) || empty($password)) {
    $response = array('success' => false, 'message' => 'All fields are required.');
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response = array('success' => false, 'message' => 'Invalid email format.');
    echo json_encode($response);
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user data into the database
$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";

if ($conn->query($sql) === true) {
    $response = array('success' => true, 'message' => 'User registered successfully.');
    echo json_encode($response);
} else {
    $response = array('success' => false, 'message' => 'Error registering user: ' . $conn->error);
    echo json_encode($response);
}

// Close the database connection
$conn->close();
?>
