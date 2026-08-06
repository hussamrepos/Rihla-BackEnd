<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/database.php";
include "../helpers/response.php";

$id = intval($_POST["id"] ?? 0);
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$is_student = intval($_POST["is_student"] ?? 0);

if ($id <= 0 || $name == "" || $email == "") {
    SendResponse(false, "ID, name, and email are required", null);
    exit;
}

if ($password == "") {
    $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ?, is_student = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssii", $name, $email, $is_student, $id);
} else {
    // Users currently use the same plain-password format as the existing mobile login.php.
    $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ?, is_student = ?, password = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssisi", $name, $email, $is_student, $password, $id);
}

if (mysqli_stmt_execute($stmt)) {
    SendResponse(true, "User updated successfully", null);
} else {
    SendResponse(false, "Could not update user", null);
}
?>
