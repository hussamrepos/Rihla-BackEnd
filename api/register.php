<?php 
include "../config/database.php";
include "../helpers/response.php";

$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];

$sql = "INSERT INTO users (name,email,password) VALUES ('$name', '$email', '$password')";
$result = mysqli_query($conn,$sql);

if ($result) {
    sendResponse(
        true,
        "user registered seccusfully ",
        null
    );
}
else {
    sendResponse(
        false,
        "registration failed ",
        null
    );
}

?>