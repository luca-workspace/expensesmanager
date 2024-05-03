<?php
//connect to database
require "connect.php";

//start session
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //get username and password from the form after a request of type POST request has been made
    $revenueAmount = $_POST["revenueAmount"];
    $revenueCategory = $_POST["revenueCategory"];
    $revenueDescription = $_POST["revenueDescription"];
    $revenueLocation = $_POST["revenueLocation"];
    $revenueDate = $_POST["revenueDate"];

    $sql = "INSERT INTO `revenues` (`revenueid`, `amount`, `fk_categoryname`, `description`, `fk_location`, `date`, `fk_userid`) 
            VALUES (NULL, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    //again, parameter binding, to prevent SQL injection attacks
    $stmt->bind_param("isssss", $revenueAmount, $revenueCategory, $revenueDescription, $revenueLocation, $revenueDate, $_SESSION["user_id"]);

    if($stmt->execute()) {
        $stmt->close();

        //return 0
        echo 0;
    }
    else //if storing was unsuccessful...
        echo -2; //...warn the user
}