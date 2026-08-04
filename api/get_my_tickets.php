<?php

include "../config/database.php";
include "../helpers/response.php";

$user_id = $_GET["user_id"];

$select = mysqli_prepare($conn, "SELECT * FROM user_tickets WHERE user_id = ? AND expires_at > NOW() ORDER BY purchased_at DESC");
mysqli_stmt_bind_param($select, "i", $user_id);
$success = mysqli_stmt_execute($select);
$result = mysqli_stmt_get_result($select);
$tickets = mysqli_fetch_all($result, MYSQLI_ASSOC);
SendResponse(true, "Tickets retrieved successfully", $tickets);
