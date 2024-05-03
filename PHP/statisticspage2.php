<?php
require "connect.php";

session_start();

//read inbound data POST
$jsonData = file_get_contents('php://input');

//decodifica the "stringified" JSON
$data = json_decode($jsonData, true);

//save the dates values
$startDate = $data['startDate'];
$endDate = $data['endDate'];
//save the array of categories
$selectedCategories = $data["categories"];

//query to get the records of the selected categories
$sql = "SELECT e.expenseid, e.amount, e.fk_categoryname, e.description, e.fk_location, e.date, ec.defaultcolor
        FROM expenses e
        JOIN expensescategories ec ON e.fk_categoryname = ec.categoryname
        WHERE e.date BETWEEN ? AND ? AND e.fk_userid = ?
              AND e.fk_categoryname IN ('" . implode("','", $selectedCategories) . "')
        ORDER BY e.date ASC";

//parameter binding, execution, and storing of the result
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $startDate, $endDate, $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();

//check if any records (rows) were returned
if($result->num_rows > 0) {
    $tbody = "";
    
    while($row = $result->fetch_assoc()) {
        //go through every record and build the tbody subparts (rows and colums) piece by piece
        $tbody .= "<td class=\"hidden sm:block\">" . $row["expenseid"] . "</td>";
        $tbody .= "<td>" . $row["amount"] . "</td>";
        $tbody .= "<td style=\"color: " . $row["defaultcolor"] . "\">" . $row["fk_categoryname"] . "</td>";
        $tbody .= "<td>" . $row["description"] . "</td>";
        $tbody .= "<td class=\"hidden sm:block\">" . $row["fk_location"] . "</td>";
        $tbody .= "<td>" . $row["date"] . "</td>";
        $tbody .= "</tr>";
    }
}
else {
    //in case no records have been stored onto the database yet...
    $tbody = "<tr class=\"text-red-700 font-semibold\">";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "<td>" . "[No data yet!]" . "</td>";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "</tr>";
}


//return
echo $tbody;