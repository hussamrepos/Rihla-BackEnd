<?php

header("Content-Type: application/json");

include "../config/database.php";
include "../helpers/response.php";

$user_id = intval($_GET["user_id"]);

$select = mysqli_prepare($conn, "SELECT * FROM user_tickets WHERE user_id = ? ORDER BY purchased_at DESC");
mysqli_stmt_bind_param($select, "i", $user_id);
mysqli_stmt_execute($select);
$result = mysqli_stmt_get_result($select);

$ride_history = mysqli_fetch_all($result, MYSQLI_ASSOC);

SendResponse(true, "Ride history retrieved successfully", $ride_history);

?>