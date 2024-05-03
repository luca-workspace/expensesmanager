<?php

//start session
session_start();

function setTheme() {
    $_SESSION["theme"] = $_POST["theme"];
    echo $_SESSION["theme"];
}

function getTheme() {
    if(isset($_SESSION["theme"]))
        echo $_SESSION["theme"];
    else
        echo "No theme set";
}

//if the theme is to be retrieved, then run getTheme()
if($_POST["theme"] === "get")
    getTheme();
//otherwise set the them passed from Javascript
else 
    setTheme();
?>