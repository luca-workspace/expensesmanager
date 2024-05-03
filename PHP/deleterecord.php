<?php
//connect to database
require "connect.php";

//start session
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //get username and password from the form after a request of type POST request has been made
    $recordID = $_POST["recordID"];
    $recordType = strtolower($_POST["recordType"]);

    $sql = "SELECT " . $recordType . "id
            FROM " . $recordType . "s
            WHERE fk_userid = ? AND " . $recordType . "id = ?";
    /*the resulting query will be:
            SELECT expenseid
                    FROM expenses
                    WHERE fk_userid = ? AND expenseid = ?
      or:
            SELECT revenueid
                    FROM revenues
                    WHERE fk_userid = ? AND expenseid = ?
      depending on the type*/

    //parameter binding and query execution
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $_SESSION["user_id"], $recordID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) { //if it exists...
        //...delete it
        $sql = "DELETE
        FROM " . $recordType . "s
        WHERE fk_userid = ? AND " . $recordType . "id = ?";

        //parameter binding and query execution
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $_SESSION["user_id"], $recordID);

        if($stmt->execute()) {
            $stmt->close();
    
            //return 0
            echo 0;
        }
    }
    else //if it doesn't exist, return an error
        echo -1;
}