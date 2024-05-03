<?php
//run the connect.php script, which performs the connection to the database
require "connect.php";

//start session
session_start();

//define the query that gathers the records from the database from the last 30 days, the most recent ones first
$sqltable = "SELECT 'Expense' AS type, expenseid AS id, amount, fk_categoryname AS category, description, fk_location AS location, date, fk_userid AS user_id
        FROM expenses
        WHERE `date` >= CURDATE() - INTERVAL 30 DAY AND `date` <= CURDATE()
            AND fk_userid = ?
        UNION ALL
        SELECT 'Revenue' AS type, revenueid AS id, amount, fk_categoryname AS category, description, fk_location AS location, date, fk_userid AS user_id
        FROM revenues
        WHERE `date` >= CURDATE() - INTERVAL 30 DAY AND `date` <= CURDATE()
            AND fk_userid = ?
        ORDER BY `date` DESC";

//execute it
$stmt = $conn->prepare($sqltable);
$stmt->bind_param("ss", $_SESSION["user_id"], $_SESSION["user_id"]);
$stmt->execute();
$resulttable = $stmt->get_result();

//define the query that gathers the homepage's main stats values, and round the values
$sqlstats = "SELECT 
        ROUND((
            SELECT SUM(amount) 
            FROM revenues 
            WHERE `date` >= CURDATE() - INTERVAL 30 DAY AND `date` <= CURDATE()
                AND fk_userid = ?
        ), 1) AS revenues,
        ROUND((
            SELECT SUM(amount) 
            FROM expenses 
            WHERE `date` >= CURDATE() - INTERVAL 30 DAY AND `date` <= CURDATE()
                AND fk_userid = ?
        ), 1) AS expenses,
        ROUND((
            SELECT SUM(amount) 
            FROM expenses 
            WHERE fk_categoryname = 'Bills' 
                AND `date` >= CURDATE() - INTERVAL 30 DAY AND `date` <= CURDATE()
                AND fk_userid = ?
        ), 1) AS bills_expenses,
        ROUND((
            SELECT SUM(amount)
            FROM expenses
            WHERE fk_categoryname = 'Food'
                AND `date` >= CURDATE() - INTERVAL 30 DAY AND `date` <= CURDATE()
                AND fk_userid = ?
        ), 1) AS food_expenses";

//execute it
$stmt = $conn->prepare($sqlstats);
$stmt->bind_param("ssss", $_SESSION["user_id"], $_SESSION["user_id"], $_SESSION["user_id"], $_SESSION["user_id"]);
$stmt->execute();
$resultstats = $stmt->get_result();

//define the query that gathers the required info about the charts
$sqlchart = "SELECT expensescategories.defaultcolor, expensescategories.categoryname, ROUND(SUM(expenses.amount), 1) AS amount
        FROM expenses
        JOIN expensescategories ON expenses.fk_categoryname = expensescategories.categoryname
        WHERE expenses.date >= CURDATE() - INTERVAL 30 DAY AND expenses.date <= CURDATE()
            AND expenses.fk_userid = ?
        GROUP BY expensescategories.categoryname
        ORDER BY expensescategories.categoryname";

//execute it
$stmt = $conn->prepare($sqlchart);
$stmt->bind_param("s", $_SESSION["user_id"]);
$stmt->execute();
$resultchart = $stmt->get_result();

//define the query that gathers the categories for the expenses
$sqlexpensescat = "SELECT categoryname
                FROM expensescategories";

//execute it
$resultexpensescat = $conn->execute_query($sqlexpensescat);

//define the query that gathers the categories for the expenses
$sqlrevenuescat = "SELECT categoryname
                FROM revenuescategories";
                
//execute it
$resultrevenuescat = $conn->execute_query($sqlrevenuescat);

//define the query that gathers the locations for both the expenses and the revenues
$sqllocations = "SELECT locationname
                FROM locations";
                
//execute it
$resultlocations = $conn->execute_query($sqllocations);

//global variables containing the lists of options
$optionsexpensescat = "<option disabled selected>Category</option>";
$optionsrevenuescat = "<option disabled selected>Category</option>";
$locations = "<option disabled selected>Location</option>";

//build the div for the options values (expenses)
while($row = $resultexpensescat->fetch_assoc())
    $optionsexpensescat .= "<option value=\"" . $row["categoryname"] . "\">" . $row["categoryname"] . "</option>";

//build the div for the options values (revenues)
while($row = $resultrevenuescat->fetch_assoc())
    $optionsrevenuescat .= "<option value=\"" . $row["categoryname"] . "\">" . $row["categoryname"] . "</option>";

//build the div for the options values (locations)
while($row = $resultlocations->fetch_assoc())
    $locations .= "<option value=\"" . $row["locationname"] . "\">" . $row["locationname"] . "</option>";

//----------------------------------------------------

//check if any records (rows) were returned
if($resulttable->num_rows > 0) {
    //initialize a string where to store HTML output (=the tbody element)
    $tbody = "<tbody id=\"tbody\">";

    //fetch data and append them to output string
    while($row = $resulttable->fetch_assoc()) {
        if(strcmp($row["type"], "Revenue") == 0) //if the record is a revenue...
            //make the text green (Tailwind's "text-green-700" class) and bold (Tailwind's "font-semibold" class)
            $tbody .= "<tr class=\"text-green-700 font-semibold\">";
        else
            $tbody .= "<tr>";

        //go through every record and build the tbody subparts (rows and colums) piece by piece
        $tbody .= "<td>" . $row["id"] . "</td>";
        $tbody .= "<td>" . $row["amount"] . "</td>";
        $tbody .= "<td>" . $row["category"] . "</td>";
        $tbody .= "<td class=\"hidden sm:table-cell\">" . $row["description"] . "</td>";
        $tbody .= "<td class=\"hidden sm:table-cell\">" . $row["location"] . "</td>";
        $tbody .= "<td>" . $row["date"] . "</td>";
        $tbody .= "</tr>";
    }
    $tbody .= "</tbody>";

    //get the stat values
    $row = $resultstats->fetch_assoc();

    //initialize the values with 0 first
    $revenues = 0;
    $expenses = 0;
    $bills_expenses = 0;
    $food_expenses = 0;

    //check if the values are actually assigned, and if they are, replace the 0's
    if(isset($row["revenues"]))
        $revenues = $row["revenues"];
    if(isset($row["expenses"]))
        $expenses = $row["expenses"];
    if(isset($row["bills_expenses"]))
        $bills_expenses = $row["bills_expenses"];
    if(isset($row["food_expenses"]))
        $food_expenses = $row["food_expenses"];
    //this was necessary to make sure that at least a 0 is present in the interface, instead of nothing (NULL)

    //build an array containing the data
    $data = array(
        "revenues" => format($revenues),
        "expenses" => format($expenses),
        "bills_expenses" => format($bills_expenses),
        "food_expenses" => format($food_expenses)
    );

    //build three arrays, one for the chart's labels (categories), one for their colors, and one for the actual values
    $chartcategorycolors = [];
    $chartlabels = [];
    $chartvalues = [];

    //populate the arrays
    while($row = $resultchart->fetch_assoc()) {
        $chartcategorycolors[] = $row["defaultcolor"];
        $chartlabels[] = $row["categoryname"];
        $chartvalues[] = $row["amount"];
    }

    //create an array containing all three
     if(count($chartvalues) > 0) {
        $chartinfo["colors"] = $chartcategorycolors;
        $chartinfo["labels"] = $chartlabels;
        $chartinfo["values"] = $chartvalues;
    }
    else {
        $chartinfo["colors"] = ["#000000"];
        $chartinfo["labels"] = ["No data"];
        $chartinfo["values"] = [0.01];
    }

    //define an array containing both the tbody, the data values, and the chart information
    $response = array(
        "tbody" => $tbody,
        "data" => $data,
        "chart" => $chartinfo,
        "categoriesexpenses" => $optionsexpensescat,
        "categoriesrevenues" => $optionsrevenuescat,
        "locations" => $locations
    );

    //the response will be encoded in the JSON format so that Javascript can distinguish the parts
    //the JSON header will be added to the response
    header('Content-Type: application/json');
    echo json_encode($response);
}
else {
    //in case no record have been stored onto the database yet
    $tbody = "<tbody id=\"tbody\">";
    $tbody .= "<tr class=\"text-red-700 font-semibold\">";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "<td>" . "[No data yet!]" . "</td>";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "<td>" . "" . "</td>";
    $tbody .= "</tr>";
    $tbody .= "</tbody>";

    $response = array(
        "tbody" => $tbody,
        "data" => $data = array(
            "revenues" => 0,
            "expenses" => 0,
            "bills_expenses" => 0,
            "food_expenses" => 0
        ),
        "chart" => $chartinfo = array(
            "colors" => ["#000000"],
            "labels" => ["No data"],
            "values" => [0.01]
        ),
        "categoriesexpenses" => $optionsexpensescat,
        "categoriesrevenues" => $optionsrevenuescat,
        "locations" => $locations
    );

    header('Content-Type: application/json');

    echo json_encode($response);
}

function format($num) {
    //if lower than 100'000, do nothing
    if($num < 100000) {
        return $num;
    }
    //if between 100'000 and 999'999, divide by 1000 and round up
    else if($num < 1000000) {
        return round($num / 1000, 2) . 'K';
    }
    //if between 1'000'000 and 99'999'999, divide by 1'000'000 and round up
    else if($num < 100000000) {
        return round($num / 1000000, 3) . 'M';
    }
    //if between 100'000'000 and 999'999'999, divide by 1'000'000 and round up
    else if($num < 1000000000) {
        return round($num / 1000000, 2) . 'M';
    }
    //if between 1'000'000'000 and 99'999'999'999, divide by 1'000'000'000 and round up
    else if($num < 99999999999)
        return round($num / 1000000000, 3) . 'B';
    //if between 100'000'000'000 and 999'999'999'999, divide by 1'000'000'000 and round up
    else {
        return round($num / 1000000000, 2) . 'B';
    }
}
?>