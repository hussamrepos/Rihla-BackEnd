<?php

header("Content-Type: application/json");

include "../config/database.php";
include "../helpers/response.php";

$user_id = intval($_GET["user_id"] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT * FROM wallet_transactions WHERE user_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$transactions = mysqli_fetch_all($result, MYSQLI_ASSOC);

SendResponse(true, "Transactions retrieved successfully", ["transactions" => $transactions]);

?>