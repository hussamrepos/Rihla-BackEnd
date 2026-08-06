<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/database.php";
include "../helpers/response.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $result = mysqli_query($conn, "SELECT ticket_name, price, duration_hours FROM ticket_types ORDER BY id");
    SendResponse(true, "Ticket prices retrieved", mysqli_fetch_all($result, MYSQLI_ASSOC));
    exit;
}

$twoHours = floatval($_POST["two_hours_price"] ?? 0);
$sevenDays = floatval($_POST["seven_days_price"] ?? 0);

if ($twoHours <= 0 || $sevenDays <= 0) {
    SendResponse(false, "Prices must be greater than zero", null);
    exit;
}

$stmt = mysqli_prepare($conn, "UPDATE ticket_types SET price = ? WHERE ticket_name = ?");
$twoName = "2-Hours Ticket";
mysqli_stmt_bind_param($stmt, "ds", $twoHours, $twoName);
mysqli_stmt_execute($stmt);

$sevenName = "7-Days Ticket";
mysqli_stmt_bind_param($stmt, "ds", $sevenDays, $sevenName);
mysqli_stmt_execute($stmt);

SendResponse(true, "Ticket prices updated", null);
?>
