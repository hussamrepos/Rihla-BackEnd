<?php

header("content-type: application/json");

include "../config/database.php";
include "../helpers/response.php";

$user_id = intval($_POST["user_id"] ?? 0);
$amount = floatval($_POST["amount"] ?? 0);

if ($user_id <= 0 || $amount <= 0) {
    SendResponse(false, "Invalid user ID or amount", null);
    exit();
}

mysqli_begin_transaction($conn);

$update = mysqli_prepare($conn, "UPDATE users SET balance = balance + ? WHERE id = ?");
mysqli_stmt_bind_param($update, "di", $amount, $user_id);
$updated = mysqli_stmt_execute($update);

if (!$updated || mysqli_stmt_affected_rows($update) <= 0) {
    mysqli_rollback($conn);
    SendResponse(false, "USER_NOT_FOUND OR BALANCE_UPDATE_FAILED", null);
    exit();
}

$type = "top_up";
$description = "wallet top_up";

$transaction = mysqli_prepare($conn, "INSERT INTO wallet_transactions (user_id, type, amount, description) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($transaction, "isds", $user_id, $type, $amount, $description);
$saved = mysqli_stmt_execute($transaction);
if (!$saved) {
    mysqli_rollback($conn);
    SendResponse(false, "trasaction could not be saved", null);
    exit();
}

mysqli_commit($conn);

$getBalance = mysqli_prepare($conn, "SELECT balance FROM users WHERE id = ?");
mysqli_stmt_bind_param($getBalance, "i", $user_id);
mysqli_stmt_execute($getBalance);
$result = mysqli_stmt_get_result($getBalance);
$user = mysqli_fetch_assoc($result);

SendResponse(true, "balance topped up successfully", ["balance" => $user["balance"]]);
