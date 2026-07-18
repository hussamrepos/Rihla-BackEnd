<?php 

include "../config/database.php";
include "../helpers/response.php";

$email = $_POST["email"];
$password = $_POST["password"];

$sql = "SELECT * FROM users WHERE email = '$email'";

$result = mysqli_query($conn,$sql);

$user = mysqli_fetch_assoc($result);


if ($user && $password == $user["password"]){

SendResponse(
    true,
    "login succesful",
    $user
);

}

else{
    SendResponse(
        false,
    "login failed",
    null
    );
}






?>