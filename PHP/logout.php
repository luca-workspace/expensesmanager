<?php
session_start();

//destroy session
session_destroy();

//redirect to the login/signup page
header("Location: ../access.php");
exit;
?>