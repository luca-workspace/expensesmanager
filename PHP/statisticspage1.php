<?php
require "connect.php";

session_start();

//get the year value sent through a POST request
$year = $_POST["year"];

//query to get the total values per category per month
$sql = "SELECT
            e.fk_categoryname AS category,
            MONTH(e.date) AS month,
            ROUND(SUM(e.amount), 2) AS total_amount
        FROM
            expenses e
        WHERE
            YEAR(e.date) = ?
            AND fk_userid = ?
        GROUP BY
            e.fk_categoryname,
            MONTH(e.date)
        ORDER BY
            e.fk_categoryname,
            month ASC";

//parameter binding, execution, and storing of the result
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $year, $_SESSION["user_id"]);
$stmt->execute();
$resultexpenseschart = $stmt->get_result();

//declaration of the components (datasets) of a Chart.js chart
//each contains a label, data, a color and the type
//each of these arrays are dedicated to either the expenses or the revenues datasets
$datasetsexpenses = array();
$datasetsrevenues = array();

//EXPENSES CHART DATA
//check if any records (rows) were returned
if($resultexpenseschart->num_rows > 0) {
    //if they were, then retrieve the expenses categories
    $sql = "SELECT categoryname, defaultcolor
            FROM expensescategories";
    $resultexpensescategories = mysqli_query($conn, $sql);
    
    //for each of the categories...
    while($row = $resultexpensescategories->fetch_assoc()) {
        //...save the name of the category and its default color (the type is knows, as the type of chart is fixed)
        $category = $row['categoryname'];
        $color = $row['defaultcolor'];

        //...declare and initialize a 12-numbers array, which will contain the total of each of the months for a specific category
        $data = array_fill(0, 12, 0);
        //...save the totals
        while($row = $resultexpenseschart->fetch_assoc()) {
            if ($row['category'] == $category) {
                $month = $row['month'] - 1; //adjust for zero-based indexing
                $amount = $row['total_amount'];
                $data[$month] = $amount;
            }
        }

        //...add a new dataset to the array
        $datasetsexpenses[] = array(
            "label" => $category,
            "data" => $data,
            "backgroundColor" => $color,
            "type" => "bar"
        );

        //reset the pointer for $resultexpenseschart to evaluate the next category
        $resultexpenseschart->data_seek(0);
    }
}

//REVENUES CHART DATA
//the same query as before but for the revenues
//the rest is equivalent, just for the revenues rather than the expenses
$sql = "SELECT
            e.fk_categoryname AS category,
            MONTH(e.date) AS month,
            ROUND(SUM(e.amount), 2) AS total_amount
        FROM
            revenues e
        WHERE
            YEAR(e.date) = ?
            AND fk_userid = ?
        GROUP BY
            e.fk_categoryname,
            MONTH(e.date)
        ORDER BY
            e.fk_categoryname,
            month ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $year, $_SESSION["user_id"]);
$stmt->execute();
$resultrevenueschart = $stmt->get_result();

//check if any records (rows) were returned
if($resultrevenueschart->num_rows > 0) {
    //if they were, then retrieve the revenues categories
    $sql = "SELECT categoryname, defaultcolor
            FROM revenuescategories";
    $resultrevenuescategories = mysqli_query($conn, $sql);

    while($row = $resultrevenuescategories->fetch_assoc()) {
        $category = $row['categoryname'];
        $color = $row['defaultcolor'];
        $data = array_fill(0, 12, 0);

        while($row = $resultrevenueschart->fetch_assoc()) {
            if ($row['category'] == $category) {
                $month = $row['month'] - 1; //adjust for zero-based indexing
                $amount = $row['total_amount'];
                $data[$month] = $amount;
            }
        }

        $datasetsrevenues[] = array(
            "label" => $category,
            "data" => $data,
            "backgroundColor" => $color,
            "type" => "bar"
        );

        //reset the pointer for $resultrevenueschart
        $resultrevenueschart->data_seek(0);
    }
}

$response = array(
    "datasetsexpenses" => $datasetsexpenses,
    "datasetsrevenues" => $datasetsrevenues
);

//the response will be encoded in the JSON format so that Javascript can distinguish the parts
//the JSON header will be added to the response
header('Content-Type: application/json');
echo json_encode($response);