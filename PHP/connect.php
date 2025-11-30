<?php
//database credentials
$servername = "localhost"; //server name/address
$username = "root"; //database username
$password = ""; //database password (null, in our case)
$database = "expensesdb"; //database name

//create connection
$conn = new mysqli($servername, $username, $password, $database);

//check connection
if($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

$conn->set_charset("utf8mb4");

//not required to close connection, as PHP automatically closes it at the end of script execution
?>
