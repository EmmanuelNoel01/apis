<?php

include("dbconnection.php");
$con = dbconnection();

if (isset($_POST["name"])) {
    $name = $_POST["name"];
} else {
    return;
}

if (isset($_POST["drug"])) {
    $drug = $_POST["drug"];
} else {
    return;
}

if (isset($_POST["prescription"])) {
    $prescription = $_POST["prescription"];
} else {
    return;
}

if (isset($_POST["days"])) {
    $days = $_POST["days"];
} else {
    return;
}

if (isset($_POST["disease"])) {
    $disease = $_POST["disease"];
} else {
    return;
}

if (isset($_POST["month"])) {
    $month = $_POST["month"]; 
} else {
    return;
}

if (isset($_POST["division"])) {
    $month = $_POST["division"]; 
} else {
    return;
}

$name = mysqli_real_escape_string($con, $name); 
$drug = mysqli_real_escape_string($con, $drug);
$prescription = mysqli_real_escape_string($con, $prescription);
$days = mysqli_real_escape_string($con, $days);
$disease = mysqli_real_escape_string($con, $disease);
$month = mysqli_real_escape_string($con, $month);
$division = mysqli_real_escape_string($con, $division);

$query = "INSERT INTO `patients` (`name`, `drug`, `prescription`, `days`, `disease`, `month`, `division`) 
          VALUES ('$name', '$drug', '$prescription', '$days', '$disease', '$month', '$division')";
$exe = mysqli_query($con, $query);

$arr = [];
if ($exe) {
    $arr["success"] = "true";
    echo "Data added to the database.";
} else {
    $arr["success"] = "false";
    echo "Failed to add data to the database.";
}
print(json_encode($arr));

?>
