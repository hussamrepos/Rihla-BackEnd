<?php 
include "../config/database.php";
include "../helpers/response.php";

$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$is_student = ($_POST["is_student"] == "true") ? 1 : 0;
$student_id = $_POST["student_id"] ?? null; // may be empty for non-students

// ---- Server-side student ID validation ----
$validStudentIds = ["202103056", "202103035"];

if ($is_student == 1 && !in_array($student_id, $validStudentIds)) {
    SendResponse(
        false,
        "Invalid student ID",
        null
    );
    exit();
}

// ---- Check for existing email (now using a prepared statement) ----
$checkEmail = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
mysqli_stmt_bind_param($checkEmail, "s", $email);
mysqli_stmt_execute($checkEmail);
$result = mysqli_stmt_get_result($checkEmail);

if (mysqli_num_rows($result) > 0) {
    SendResponse(
        false,
        "There is already a user with this email",
        null
    );
    exit();
}

// ---- Insert new user (prepared statement) ----
$insert = mysqli_prepare(
    $conn,
    "INSERT INTO users (name, email, password, is_student, student_id) VALUES (?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param($insert, "sssis", $name, $email, $password, $is_student, $student_id);
$success = mysqli_stmt_execute($insert);

if ($success) {
    SendResponse(
        true,
        "user registered successfully",
        null
    );
} else {
    SendResponse(
        false,
        "registration failed",
        null
    );
}
?>