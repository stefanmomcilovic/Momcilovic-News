<?php
ob_start(); //Turns on output buffering 
session_start(); //Session start

date_default_timezone_set("Europe/London");

$dbname = "momcilovicnews_n";
$host = "localhost";
$username = "momcilovicnews_n";
$password = "07pf0dwU0k";

try {
    $con = new PDO("mysql:dbname=$dbname;host=$host", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
