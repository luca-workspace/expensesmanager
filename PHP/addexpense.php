<?php
//connect to database
require "connect.php";

//start session
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //get username and password from the form after a request of type POST request has been made
    $expenseAmount = $_POST["expenseAmount"];
    $expenseCategory = $_POST["expenseCategory"];
    $expenseDescription = $_POST["expenseDescription"];
    $expenseLocation = $_POST["expenseLocation"];
    $expenseDate = $_POST["expenseDate"];

    $sql = "INSERT INTO `expenses` (`expenseid`, `amount`, `fk_categoryname`, `description`, `fk_location`, `date`, `fk_userid`) 
            VALUES (NULL, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    //again, parameter binding, to prevent SQL injection attacks
    $stmt->bind_param("isssss", $expenseAmount, $expenseCategory, $expenseDescription, $expenseLocation, $expenseDate, $_SESSION["user_id"]);

    if($stmt->execute()) {
        $stmt->close();

        //return 0
        echo 0;
    }
    else //if storing was unsuccessful...
        echo -1; //...warn the user
}