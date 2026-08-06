<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/database.php";
include "../helpers/response.php";

$id = intval($_POST["id"] ?? 0);
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");

if ($id <= 0 || $name == "" || $email == "") {
    SendResponse(false, "ID, name, and email are required", null);
    exit;
}

$stmt = mysqli_prepare($conn, "UPDATE admins SET name = ?, email = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $id);

if (mysqli_stmt_execute($stmt)) {
    SendResponse(true, "Admin profile updated", null);
} else {
    SendResponse(false, "Could not update admin profile", null);
}
?>
