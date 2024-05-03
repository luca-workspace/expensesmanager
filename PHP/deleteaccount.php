<?php
//run connect.php and start session
require "connect.php";
session_start();

//query to delete account from the database
$sql = "DELETE FROM users WHERE users.userid = ?";

//parameter binding and execution
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["user_id"]);
$stmt->execute();

session_destroy();