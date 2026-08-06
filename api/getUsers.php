<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../config/database.php";
include "../helpers/response.php";

$sql = "
    SELECT
        users.id,
        users.name,
        users.email,
        users.is_student,
        users.student_id,
        users.balance,

        (
            SELECT COUNT(*)
            FROM saved_stations
            WHERE saved_stations.user_id = users.id
        ) AS saved_stations_count,

        (
            SELECT COUNT(*)
            FROM user_tickets
            WHERE user_tickets.user_id = users.id
        ) AS tickets_count

    FROM users
    ORDER BY users.id DESC
";

$result = mysqli_query($conn, $sql);

$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

SendResponse(
    true,
    "Users retrieved successfully",
    $users
);

?>