<?php

include "../config/database.php";


$user_id = $_POST['user_id'];
$station_id = $_POST['station_id'];


$sql = "
DELETE FROM saved_stations
WHERE user_id = '$user_id'
AND station_id = '$station_id'
";


if(mysqli_query($conn, $sql)){

    echo json_encode([
        "success"=>true,
        "message"=>"Station removed"
    ]);

}else{

    echo json_encode([
        "success"=>false,
        "message"=>"Error removing station"
    ]);

}

?>