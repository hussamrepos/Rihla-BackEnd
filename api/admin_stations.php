<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/database.php";
include "../helpers/response.php";

$action = $_REQUEST["action"] ?? "list";

if ($action === "list") {
    $result = mysqli_query($conn, "SELECT * FROM stations ORDER BY line, position, id");
    SendResponse(true, "Stations retrieved", mysqli_fetch_all($result, MYSQLI_ASSOC));
    exit;
}

$id = intval($_POST["id"] ?? 0);
$name = trim($_POST["name"] ?? "");
$line = trim($_POST["line"] ?? "");
$position = intval($_POST["position"] ?? 0);

if ($action === "add" && $name != "" && $line != "") {
    $stmt = mysqli_prepare($conn, "INSERT INTO stations (name, line, position) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssi", $name, $line, $position);
} elseif ($action === "update" && $id > 0 && $name != "") {
    $stmt = mysqli_prepare($conn, "UPDATE stations SET name = ?, position = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sii", $name, $position, $id);
} elseif ($action === "delete" && $id > 0) {
    $stmt = mysqli_prepare($conn, "DELETE FROM stations WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
} else {
    SendResponse(false, "Invalid station request", null);
    exit;
}

if (mysqli_stmt_execute($stmt)) {
    SendResponse(true, "Station updated successfully", null);
} else {
    SendResponse(false, "Could not update station", null);
}
?>
