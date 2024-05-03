<?php
//connect to database
require "connect.php";

//start session
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //get username and password from the form after a request of type POST request has been made
    $username = $_POST["username"];
    $password = $_POST["password"];

    //check the credentials
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    //parameter binding, to prevent SQL injection attacks
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if($result->num_rows > 0) { //if such username exists...
        $row = $result->fetch_assoc();

        //hash calculation
        $hashcode = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);
        //...check the password
        if(password_verify($password, $row["hashcode"])) {
            $_SESSION["user_id"] = $username;

            echo 0;
            exit;
        }
        else
            echo -1;
    }
    else
        echo -2;
}
?>