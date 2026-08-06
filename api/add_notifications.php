<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../config/database.php";
include "../helpers/response.php";

$title = $_POST["title"] ?? "";
$message = $_POST["message"] ?? "";
$user_id = $_POST["user_id"] ?? null;

if ($title == "" || $message == "") {
    SendResponse(false, "Title and message are required", null);
    exit;
}

if ($user_id != null && $user_id != "") {
    $sql = "INSERT INTO notifications (title, message, user_id)
            VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssi",
        $title,
        $message,
        $user_id
    );
} else {
    $sql = "INSERT INTO notifications (title, message, user_id)
            VALUES (?, ?, NULL)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $title,
        $message
    );
}

if (mysqli_stmt_execute($stmt)) {
    SendResponse(true, "Notification sent successfully", null);
} else {
    SendResponse(false, "Failed to send notification", null);
}

?>