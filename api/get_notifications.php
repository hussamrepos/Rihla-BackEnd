<?php

include "../config/database.php";

$sql = "SELECT * FROM notifications ORDER BY created_at DESC";

$result = mysqli_query($conn,$sql);

$notifications = [];

while($row = mysqli_fetch_assoc($result)){

    $notifications[] = $row;

}


echo json_encode($notifications);

?>

