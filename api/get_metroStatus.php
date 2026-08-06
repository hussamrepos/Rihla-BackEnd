<?php

include "../config/database.php";


$sql = "SELECT * FROM metro_status";


$result = mysqli_query($conn,$sql);


$data=[];


while($row=mysqli_fetch_assoc($result)){

    $data[]=$row;

}


echo json_encode($data);


?>