<?php

header("Content-Type: application/json");

include "../config/database.php";
include "../helpers/response.php";

$user_id = intval($_GET["user_id"] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT balance FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    SendResponse(false, "user not found", null);
} else {
    SendResponse(true, "Balance retrieved successfully", ["balance" => $user["balance"]]);
}

?>