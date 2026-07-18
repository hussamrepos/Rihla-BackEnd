<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "rihla";


$conn = mysqli_connect(

$host,
$username,
$password,
$database

);

if (!$conn){
    die("Datbase conncetion failed ");
}

?>