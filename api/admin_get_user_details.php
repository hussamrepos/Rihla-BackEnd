<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../config/database.php";
include "../helpers/response.php";

$user_id = $_GET["user_id"] ?? 0;

if ($user_id == 0) {
    SendResponse(false, "User ID is required", null);
    exit;
}

$userSql = "SELECT id, name, email, is_student, student_id, balance
            FROM users
            WHERE id = ?";

$userStmt = mysqli_prepare($conn, $userSql);

mysqli_stmt_bind_param($userStmt, "i", $user_id);

mysqli_stmt_execute($userStmt);

$userResult = mysqli_stmt_get_result($userStmt);

$user = mysqli_fetch_assoc($userResult);

if (!$user) {
    SendResponse(false, "User not found", null);
    exit;
}

$stationsSql = "
    SELECT stations.*
    FROM saved_stations
    JOIN stations ON saved_stations.station_id = stations.id
    WHERE saved_stations.user_id = ?
";

$stationsStmt = mysqli_prepare($conn, $stationsSql);

mysqli_stmt_bind_param($stationsStmt, "i", $user_id);

mysqli_stmt_execute($stationsStmt);

$stationsResult = mysqli_stmt_get_result($stationsStmt);

$stations = mysqli_fetch_all($stationsResult, MYSQLI_ASSOC);

$ticketsSql = "
    SELECT *
    FROM user_tickets
    WHERE user_id = ?
    ORDER BY purchased_at DESC
";

$ticketsStmt = mysqli_prepare($conn, $ticketsSql);

mysqli_stmt_bind_param($ticketsStmt, "i", $user_id);

mysqli_stmt_execute($ticketsStmt);

$ticketsResult = mysqli_stmt_get_result($ticketsStmt);

$tickets = mysqli_fetch_all($ticketsResult, MYSQLI_ASSOC);

$data = [
    "user" => $user,
    "stations" => $stations,
    "tickets" => $tickets
];

SendResponse(
    true,
    "User details retrieved successfully",
    $data
);

?>