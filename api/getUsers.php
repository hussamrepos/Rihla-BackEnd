<?php 
include "../config/database.php";
include "../helpers/response.php";

$sql = " SELECT id, name , email FROM users";

$result = mysqli_query($conn, $sql);

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

SendResponse(
    true,
    "users retrived success",
    $users
);




?>