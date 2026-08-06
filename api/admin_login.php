<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../config/database.php";
include "../helpers/response.php";

$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

if ($email == "" || $password == "") {
    SendResponse(false, "Email and password are required", null);
    exit;
}

$sql = "SELECT * FROM admins WHERE email = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$admin = mysqli_fetch_assoc($result);

if ($admin && password_verify($password, $admin["password"])) {
    unset($admin["password"]);

    SendResponse(
        true,
        "Admin login successful",
        $admin
    );
} else {
    SendResponse(
        false,
        "Invalid admin email or password",
        null
    );
}

?>