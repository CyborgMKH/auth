<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "auth_acs";
$conn = new mysqli($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}
 
?>