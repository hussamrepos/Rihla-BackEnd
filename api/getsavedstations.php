<?php

include "../config/database.php";

$user_id = $_GET['user_id'];

$sql = "
SELECT stations.*
FROM saved_stations
JOIN stations
ON saved_stations.station_id = stations.id
WHERE saved_stations.user_id = '$user_id'
";


$result = mysqli_query($conn, $sql);


$savedStations = [];


while($row = mysqli_fetch_assoc($result)){

    $savedStations[] = $row;

}


echo json_encode($savedStations);

?>