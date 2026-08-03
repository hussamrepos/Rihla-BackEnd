<?php

include "../config/database.php";


$user_id = $_POST['user_id'];
$station_id = $_POST['station_id'];


$sql = "INSERT INTO saved_stations(user_id, station_id)
        VALUES('$user_id','$station_id')";


if(mysqli_query($conn, $sql)){

    echo json_encode([
        "success"=>true,
        "message"=>"Station saved"
    ]);

}else{

    echo json_encode([
        "success"=>false,
        "message"=>"Error saving station"
    ]);

}

?>