<?php
function dbconnection(){
    $con=mysqli_connect("localhost","root","","hosptial");
    return $con;
}
?>