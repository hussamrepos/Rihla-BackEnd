<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/database.php";
include "../helpers/response.php";

$id = intval($_POST["id"] ?? 0);
$current = $_POST["current_password"] ?? "";
$new = $_POST["new_password"] ?? "";

$stmt = mysqli_prepare($conn, "SELECT password FROM admins WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$admin || !password_verify($current, $admin["password"])) {
    SendResponse(false, "Current password is incorrect", null);
    exit;
}

$hash = password_hash($new, PASSWORD_DEFAULT);
$update = mysqli_prepare($conn, "UPDATE admins SET password = ? WHERE id = ?");
mysqli_stmt_bind_param($update, "si", $hash, $id);

if (mysqli_stmt_execute($update)) {
    SendResponse(true, "Password updated", null);
} else {
    SendResponse(false, "Could not update password", null);
}
?>
