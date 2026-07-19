<?php 
include "../config/database.php";
include "../helpers/response.php";

$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$is_student = ($_POST["is_student"] == "true") ? 1 : 0;

$checkEmail = "SELECT * FROM users WHERE email = '$email'";

$result = mysqli_query($conn,$checkEmail);



if (mysqli_num_rows($result) > 0){
    SendResponse(
        false,
        "There is already a user with this email",
        null
    );

    exit();


}

$sql = "INSERT INTO users (name,email,password,is_student) VALUES ('$name', '$email', '$password','$is_student')";

$sqlquery= mysqli_query($conn,$sql);


if ($sqlquery) {
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