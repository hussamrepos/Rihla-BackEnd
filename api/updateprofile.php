<?php

include "../config/database.php";
include "../helpers/response.php";


$id = $_POST["id"];
$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];



if($password == ""){

$query = "
UPDATE users 
SET 
name='$name',
email='$email'
WHERE id='$id'
";

}

else{

$query = "
UPDATE users 
SET 
name='$name',
email='$email',
password='$password'
WHERE id='$id'
";

}



$result = mysqli_query($conn,$query);


if($result){

SendResponse(true,"Profile updated",null);

}

else{

SendResponse(false,"Update failed",null);

}

?>