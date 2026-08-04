<?php

header("Content-Type: application/json");

include "../config/database.php";
include "../helpers/response.php";

$user_id = intval($_POST["user_id"] ?? 0);
$ticket_name = $_POST["ticket_name"] ?? "";

// The server owns the real prices and durations.
$tickets = [
    "2-Hours Ticket" => [
        "price" => 200,
        "duration_hours" => 2
    ],
    "7-Days Ticket" => [
        "price" => 2000,
        "duration_hours" => 168
    ]
];

if ($user_id <= 0 || !isset($tickets[$ticket_name])) {
    SendResponse(false, "Invalid user or ticket", null);
    exit();
}

// Get the user's balance and student status.
$getUser = mysqli_prepare(
    $conn,
    "SELECT balance, is_student FROM users WHERE id = ?"
);

mysqli_stmt_bind_param($getUser, "i", $user_id);
mysqli_stmt_execute($getUser);

$result = mysqli_stmt_get_result($getUser);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    SendResponse(false, "User not found", null);
    exit();
}

$price = $tickets[$ticket_name]["price"];
$duration_hours = $tickets[$ticket_name]["duration_hours"];

// Students automatically get 50% off.
if ($user["is_student"] == 1) {
    $price = $price / 2;
}

if ($user["balance"] < $price) {
    SendResponse(false, "Insufficient balance", null);
    exit();
}

$purchased_at = date("Y-m-d H:i:s");
$expires_at = date(
    "Y-m-d H:i:s",
    strtotime("+$duration_hours hours")
);

mysqli_begin_transaction($conn);

// 1. Deduct the ticket price.
$deduct = mysqli_prepare(
    $conn,
    "UPDATE users
     SET balance = balance - ?
     WHERE id = ? AND balance >= ?"
);

mysqli_stmt_bind_param($deduct, "did", $price, $user_id, $price);
$deducted = mysqli_stmt_execute($deduct);

if (!$deducted || mysqli_stmt_affected_rows($deduct) === 0) {
    mysqli_rollback($conn);
    SendResponse(false, "Could not deduct balance", null);
    exit();
}

// 2. Create the ticket.
$insertTicket = mysqli_prepare(
    $conn,
    "INSERT INTO user_tickets
     (user_id, ticket_name, price, purchased_at, expires_at)
     VALUES (?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $insertTicket,
    "isdss",
    $user_id,
    $ticket_name,
    $price,
    $purchased_at,
    $expires_at
);

$ticketSaved = mysqli_stmt_execute($insertTicket);

if (!$ticketSaved) {
    mysqli_rollback($conn);
    SendResponse(false, "Could not create ticket", null);
    exit();
}

// 3. Save the negative wallet activity.
$type = "ticket_purchase";
$amount = -$price;
$description = "$ticket_name purchase";

$saveTransaction = mysqli_prepare(
    $conn,
    "INSERT INTO wallet_transactions
     (user_id, type, amount, description)
     VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $saveTransaction,
    "isds",
    $user_id,
    $type,
    $amount,
    $description
);

$transactionSaved = mysqli_stmt_execute($saveTransaction);

if (!$transactionSaved) {
    mysqli_rollback($conn);
    SendResponse(false, "Could not save transaction", null);
    exit();
}

mysqli_commit($conn);

$newBalance = $user["balance"] - $price;

SendResponse(true, "Ticket purchased successfully", [
    "balance" => $newBalance,
    "ticket_name" => $ticket_name,
    "price" => $price,
    "expires_at" => $expires_at
]);
