<?php
//connect to database
require "connect.php";

//start session
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //get username and password from the form after a request of type POST request has been made
    $username = $_POST["username"];
    $password = $_POST["password"];

    //register user
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    //parameter binding, to prevent SQL injection attacks
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if($result->num_rows > 0)
        echo -1;
    else {
        //hash calculation
        $hashcode = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);

        $sql = "INSERT INTO users (userid, hashcode) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        //again, parameter binding, to prevent SQL injection attacks
        $stmt->bind_param("ss", $username, $hashcode);

        if($stmt->execute()) { //if registration is successful...
            //...save session value
            $_SESSION["user_id"] = $username;

            //...go to the homepage
            $stmt->close();
            echo 0;
        }
        else //if registration was unsuccessful...
            echo -2; //...warn the user
    }
}
?>